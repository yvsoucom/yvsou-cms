<?php
require __DIR__ . '/vendor/autoload.php';

use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Param; // <-- corrected
use PhpParser\Error;

$testsDir = __DIR__ . '/tests/Unit';
$appDir = __DIR__ . '/app';

if (!is_dir($testsDir)) {
    echo "Tests directory not found: $testsDir\n";
    exit(1);
}

// --- get PHP files recursively ---
function getPhpFiles($dir) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }
    return $files;
}

// --- extract class info ---
function parseClassInfo($filePath) {
    $code = file_get_contents($filePath);
    $parser = (new ParserFactory())->createForNewestSupportedVersion();
    try {
        $ast = $parser->parse($code);
    } catch (Error $e) {
        echo "Parse error in $filePath: " . $e->getMessage() . "\n";
        return null;
    }

    $info = [
        'namespace' => null,
        'class' => null,
        'constructorParams' => [],
        'methods' => []
    ];

    $traverser = new NodeTraverser();
    $traverser->addVisitor(new class($info) extends NodeVisitorAbstract {
        public $info;
        public function __construct(&$info) { $this->info = &$info; }
        public function enterNode(Node $node) {
            if ($node instanceof Namespace_) {
                $this->info['namespace'] = $node->name ? $node->name->toString() : null;
            }
            if ($node instanceof Class_) {
                $this->info['class'] = $node->name->toString();
            }
            if ($node instanceof ClassMethod) {
                $name = $node->name->toString();
                if ($name === '__construct') {
                    $this->info['constructorParams'] = $node->params;
                } elseif ($node->isPublic()) {
                    $this->info['methods'][] = [
                        'name' => $name,
                        'params' => $node->params,
                        'returnType' => $node->returnType ?? null
                    ];
                }
            }
        }
    });

    $traverser->traverse($ast);
    return $info;
}

// --- generate dummy value ---
function dummyValue(Param $param) {
    $type = $param->type;

    if ($type instanceof Node\Name) {
        $class = '\\' . $type->toString();
        return "\$this->createMock($class::class)";
    }
    if ($type instanceof Node\Identifier) {
        switch ($type->name) {
            case 'string': 
                if (str_contains(strtolower($param->var->name), 'email')) return "'test@example.com'";
                return "'sample'";
            case 'int': return rand(1, 100);
            case 'float': return rand(1, 100) / 1.0;
            case 'bool': return 'true';
            case 'array': return '[]';
            default: return 'null';
        }
    }

    return 'null';
}

// --- generate assertion based on return type ---
function assertionForReturnType($returnType) {
    if (!$returnType) return '$this->assertNotNull($result);';
    if ($returnType instanceof Node\Identifier) {
        switch ($returnType->name) {
            case 'array': return '$this->assertIsArray($result);';
            case 'string': return '$this->assertIsString($result);';
            case 'int': return '$this->assertIsInt($result);';
            case 'float': return '$this->assertIsFloat($result);';
            case 'bool': return '$this->assertIsBool($result);';
            case 'void': return '// void return, nothing to assert';
            default: return '$this->assertNotNull($result);';
        }
    }
   if ($returnType instanceof Node\Name) {
        $class = '\\' . $returnType->toString();
        return "\$this->assertInstanceOf($class::class, \$result);";
    }
    return '$this->assertNotNull($result);';
}

// --- main loop ---
foreach (getPhpFiles($testsDir) as $testFile) {
    $content = file_get_contents($testFile);
    if (strpos($content, "markTestSkipped('Auto generated')") === false) continue;

    preg_match('/class\s+([A-Za-z0-9_]+)Test/', $content, $matches);
    if (!$matches) continue;

    $testClass = $matches[1];

    // find original class
    $classFile = null;
    foreach (getPhpFiles($appDir) as $cf) {
        if (pathinfo($cf, PATHINFO_FILENAME) === $testClass) {
            $classFile = $cf;
            break;
        }
    }
    if (!$classFile) {
        echo "Original class not found for $testClass\n";
        continue;
    }

    $classInfo = parseClassInfo($classFile);
    if (!$classInfo) continue;

    // generate constructor
    $constructorArgs = [];
    foreach ($classInfo['constructorParams'] as $param) {
        $constructorArgs[] = dummyValue($param);
    }
    $constructorArgString = implode(', ', $constructorArgs);

    // replace skipped tests with real calls
    $updatedContent = preg_replace_callback(
        "/public function (test_[A-Za-z0-9_]+)\(\)\s*{\s*\\\$this->markTestSkipped\('Auto generated'\);\s*}/",
        function ($m) use ($testClass, $classInfo, $constructorArgString) {
            $methodName = str_replace('test_', '', $m[1]);

            // find method info
            $methodInfo = null;
            foreach ($classInfo['methods'] as $mtd) {
                if ($mtd['name'] === $methodName) {
                    $methodInfo = $mtd;
                    break;
                }
            }

            $paramArgs = [];
            if ($methodInfo) {
                foreach ($methodInfo['params'] as $p) {
                    $paramArgs[] = dummyValue($p);
                }
            }
            $paramArgString = implode(', ', $paramArgs);
            $assertion = $methodInfo ? assertionForReturnType($methodInfo['returnType']) : '$this->assertNotNull($result);';

            $code = "{\n";
            $code .= "        // Auto-filled placeholder\n";
            $code .= "        \$this->{$testClass} = new {$testClass}($constructorArgString);\n";
            $code .= "        \$result = \$this->{$testClass}->$methodName($paramArgString);\n";
            $code .= "        $assertion\n";
            $code .= "    }";

            return "public function {$m[1]}() $code";
        },
        $content
    );

    file_put_contents($testFile, $updatedContent);
    echo "Filled smart test: $testFile\n";
}

echo "All placeholder tests auto-filled with dummy calls and assertions.\n";