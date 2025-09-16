SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
SPDX-FileCopyrightText: 2025  
SPDX-FileContributor: Lican Huang
SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

# yvsou-cms – Installation Guide
Welcome to yvsou-cms!



You can choose lean or vendor-included install based on your server.

✅ Option 1: Lean Installer
Use this if your server has Composer.

Download:
Get the latest installer-vX.Y.Z.zip from Releases.

Upload & Unzip:
Upload the ZIP to your server and extract it.

http://your.com/install

if server has composer  OK,
otherwise,
in shell run ./composer.sh

http://your.com/install again

✅ Option 2: Vendor-Included Installer
Use this if your server does not have Composer.

Download:
Get the latest installvendor-vX.Y.Z.zip from Releases.

Upload & Unzip:
Upload the ZIP and extract it.
✅ The vendor/ directory is already included!

http://your.com/install

After install success, login in admin user , and go to dashboard, set email settings.

✅ How to Verify the SHA-256 Checksum
Before you install, it’s good practice to verify the file integrity:

# 1️⃣ Download the .zip and the .sha256 file from the GitHub Release page
#    For example: installer-v1.0.0.zip and checksums-sha256.txt

# 2️⃣ Calculate the SHA-256 checksum of the downloaded file:
sha256sum installer-v1.0.0.zip

# 3️⃣ Compare the output to the value in checksums-sha256.txt:
#    It should match exactly. For example:
#    1234567890abcdef...  installer-v1.0.0.zip

# On Mac, use:
shasum -a 256 installer-v1.0.0.zip

# On Windows (PowerShell):
Get-FileHash installer-v1.0.0.zip -Algorithm SHA256

🔄 How to Update
Check for updates:
We provide automatic version checks via GitHub API.

Download the new release and check config and dbscripts version.
if no chnages, 
Your custom configs (.env, config/) won’t be overwritten if you keep them separate!
if ther are any change,
update the changes automatically.