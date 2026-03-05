<?php

require __DIR__ . '/vendor/autoload.php';

use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Error;

$appDir = __DIR__ . '/app';
$testDir = __DIR__ . '/tests/Unit';

if (!is_dir($testDir)) mkdir($testDir, 0755, true);

function getPhpFiles($dir)
{
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

function parseClass($code)
{
    $parser = (new ParserFactory())->createForNewestSupportedVersion();

    try {
        $ast = $parser->parse($code);
    } catch (Error $e) {
        echo "Parse error: {$e->getMessage()}\n";
        return null;
    }

    $className = null;
    $namespace = null;
    $methods = [];

    $traverser = new NodeTraverser();

    $traverser->addVisitor(new class($className, $namespace, $methods) extends NodeVisitorAbstract {

        public $className;
        public $namespace;
        public $methods;

        public function __construct(&$className, &$namespace, &$methods)
        {
            $this->className = &$className;
            $this->namespace = &$namespace;
            $this->methods = &$methods;
        }

        public function enterNode(Node $node)
        {
            if ($node instanceof Namespace_) {
                $this->namespace = $node->name->toString();
            }

            if ($node instanceof Class_) {
                $this->className = $node->name->toString();
            }

           if ($node instanceof ClassMethod) {

                $methodName = $node->name->toString();

                if ($node->isPublic() && $methodName !== '__construct') {
                    $this->methods[] = $methodName;
                }

            }
        }
    });

    $traverser->traverse($ast);

    return [
        'class' => $className,
        'namespace' => $namespace,
        'methods' => $methods
    ];
}

foreach (getPhpFiles($appDir) as $file) {

    echo "SOURCE: $file\n";

    $code = file_get_contents($file);

    $info = parseClass($code);

    if (!$info || !$info['class']) {
        echo "Skipped (no class)\n\n";
        continue;
    }

    $class = $info['class'];
    $namespace = $info['namespace'];
    $methods = $info['methods'];

    $relative = str_replace($appDir . '/', '', $file);

    $testFile = $testDir . '/' . preg_replace('/\.php$/', 'Test.php', $relative);

    echo "TEST  : $testFile\n";

    $testNamespace = "Tests\\Unit\\" . dirname($relative);
    $testNamespace = str_replace('/', '\\', $testNamespace);

    $testCode = "<?php\n\n";

    $testCode .= "namespace $testNamespace;\n\n";

    $testCode .= "use PHPUnit\\Framework\\TestCase;\n";
    $testCode .= "use PHPUnit\\Framework\\Attributes\\Test;\n";

    if ($namespace) {
        $testCode .= "use $namespace\\$class;\n";
    }

    $testCode .= "\nclass {$class}Test extends TestCase\n{\n";

    $testCode .= "    protected \${$class};\n\n";

    $testCode .= "    protected function setUp(): void\n";
    $testCode .= "    {\n";
    $testCode .= "        parent::setUp();\n";
    $testCode .= "        \$this->{$class} = new {$class}();\n";
    $testCode .= "    }\n\n";

    foreach ($methods as $method) {

        $testCode .= "    #[Test]\n";
        $testCode .= "    public function test_$method()\n";
        $testCode .= "    {\n";
        $testCode .= "        \$this->markTestIncomplete('Auto generated');\n";
        $testCode .= "        // \$this->{$class}->$method();\n";
        $testCode .= "    }\n\n";

    }

    $testCode .= "}\n";

    if (!is_dir(dirname($testFile))) {
        mkdir(dirname($testFile), 0755, true);
    }

    file_put_contents($testFile, $testCode);

    echo "Generated ✔\n\n";
}

echo "DONE.\n"; 