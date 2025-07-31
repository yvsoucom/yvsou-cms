#!/bin/bash

# SPDX-FileCopyrightText:  Auto generated
# SPDX-License-Identifier: MIT

# File: update-changelog.sh
# Usage: ./update-changelog.sh [since_tag|--dry-run]

CHANGELOG_FILE="CHANGELOG.md"
TMP_NEW=".changelog.new"
TMP_OLD=".changelog.old"

# Optional dry run
if [[ "$1" == "--dry-run" ]]; then
  DRY_RUN=true
  shift
fi

# Detect the since-ref tag or default
if [ -n "$1" ]; then
  SINCE_REF="$1"
else
  SINCE_REF=$(git describe --tags --abbrev=0 2>/dev/null || echo "HEAD~10")
fi

# Generate changelog entry
{
  echo ""
  echo "## $(date +'%Y-%m-%d')"
  echo ""
  git log --pretty=format:"- %s (%h)" --no-merges "$SINCE_REF"..HEAD \
    | grep -vE "Merge (branch|pull request|tag)"
  echo ""
} > "$TMP_NEW"

# If empty, stop here
if ! grep -q "^- " "$TMP_NEW"; then
  echo "No new commit messages to add since $SINCE_REF."
  rm -f "$TMP_NEW"
  exit 0
fi

# Show preview and exit if dry-run
if [ "$DRY_RUN" = true ]; then
  echo "Dry run: generated changelog section (not written):"
  cat "$TMP_NEW"
  rm -f "$TMP_NEW"
  exit 0
fi

# Insert new section after "# Changelog" heading
if [ -f "$CHANGELOG_FILE" ]; then
  awk '
    /^# Changelog/ { print; system("cat '"$TMP_NEW"'"); next }
    { print }
  ' "$CHANGELOG_FILE" > "$TMP_OLD"
  mv "$TMP_OLD" "$CHANGELOG_FILE"
else
  echo "# Changelog" > "$CHANGELOG_FILE"
  cat "$TMP_NEW" >> "$CHANGELOG_FILE"
fi

rm -f "$TMP_NEW"
echo "✅ Changelog updated in $CHANGELOG_FILE (changes since $SINCE_REF)"
 