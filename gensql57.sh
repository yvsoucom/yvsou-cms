# SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
  
# SPDX-FileContributor: Lican Huang
# SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

# Copy and replace: create a new file instead of in-place
sed 's/utf8mb4_0900_ai_ci/utf8mb4_unicode_ci/g' install.sql > install57.sql
 