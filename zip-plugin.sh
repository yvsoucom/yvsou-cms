#!/bin/bash

# SPDX-FileCopyrightText:  Auto generated
# SPDX-License-Identifier: MIT
#!/bin/bash

# Check if plugin name is provided
# usage example  ./zip-plugin.sh MoneyPlugin

if [ -z "$1" ]; then
    echo "Usage: $0 <PLUGIN_NAME>"
    exit 1
fi

PLUGIN_NAME="$1"
PLUGIN_DIR="plugins/$PLUGIN_NAME"
OUTPUT_DIR="zipped_plugins"
OUTPUT_FILE="$OUTPUT_DIR/${PLUGIN_NAME}.zip"

# Create output directory if it doesn't exist
mkdir -p "$OUTPUT_DIR"

# Check if plugin directory exists
if [ ! -d "$PLUGIN_DIR" ]; then
    echo "Error: Plugin directory '$PLUGIN_DIR' does not exist."
    exit 1
fi

# Remove old zip if exists
if [ -f "$OUTPUT_FILE" ]; then
    rm "$OUTPUT_FILE"
fi

# Create zip file (recursive)
zip -r "$OUTPUT_FILE" "$PLUGIN_DIR"

echo "✅ Plugin '$PLUGIN_NAME' has been zipped to $OUTPUT_FILE"
