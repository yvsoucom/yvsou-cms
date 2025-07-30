#!/bin/bash

# SPDX-FileCopyrightText:  Auto generated
# SPDX-License-Identifier: MIT

# File: update-changelog.sh
# Usage: ./update-changelog.sh [since_tag]

CHANGELOG_FILE="CHANGELOG.md"
TMP_NEW=".changelog.new"
TMP_OLD=".changelog.old"

# Get reference point (either provided tag or last changelog entry)
SINCE_REF=${1:-$(grep -m1 -A1 "## " "$CHANGELOG_FILE" 2>/dev/null | tail -n1 | cut -d' ' -f2 || echo "HEAD~10")}

# Generate new changes
{
  echo ""
  echo "## $(date +'%Y-%m-%d')"
  echo ""
  git log --pretty=format:"- %s (%h)" --no-merges "$SINCE_REF"..HEAD \
    | grep -vE "Merge (branch|pull request|tag)"
  echo ""
} > "$TMP_NEW"

# Insert after # Changelog line
if [ -f "$CHANGELOG_FILE" ]; then
  awk '
    /^# Changelog/ {print; system("cat '"$TMP_NEW"'"); next} 
    {print}
  ' "$CHANGELOG_FILE" > "$TMP_OLD"
  
  mv "$TMP_OLD" "$CHANGELOG_FILE"
else
  echo "# Changelog" > "$CHANGELOG_FILE"
  cat "$TMP_NEW" >> "$CHANGELOG_FILE"
fi

rm "$TMP_NEW"
echo "Added new entries to $CHANGELOG_FILE (changes since $SINCE_REF)"