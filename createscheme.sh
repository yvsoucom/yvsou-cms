 
# SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
  
# SPDX-FileContributor: Lican Huang
# SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary
 
mysqldump -u your_user -p --no-data --skip-add-drop-table your_database \
| sed 's/^CREATE TABLE /CREATE TABLE IF NOT EXISTS /' \
| sed '1i-- SQL Version: sql-vx.x.x \n-- Generated on: '"$(date -u)" \
> install.sql

#for mac for example
mysqldump -u root -p --no-data --skip-add-drop-table yvsou \
| sed 's/^CREATE TABLE /CREATE TABLE IF NOT EXISTS /' \
| sed '1i\
-- SQL Version: sql-v1.1.0\
-- Generated on: '"$(date -u)" \
> install.sql
