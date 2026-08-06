# Karma System for phpBB [![Tests](https://github.com/vinny/karma/actions/workflows/tests.yml/badge.svg)](https://github.com/vinny/karma/actions/workflows/tests.yml)

Karma System adds post voting to phpBB, letting members upvote or downvote posts and highlighting active contributors across your board.

## Features

- **Post voting:** Allow registered users to upvote or downvote posts using AJAX without reloading the page.
- **Member rankings:** Show a dedicated karma leaderboard page (`/karma/ranking`) and display top leaders on the board index.
- **Moderator Control Panel (MCP):** View a detailed vote log for any user, reset cast or received votes, and adjust karma balances when needed.
- **Admin Control Panel (ACP):** Toggle the extension, enable or disable downvotes, set a voting flood interval, exclude specific forums, and run database maintenance tasks.
- **Notifications:** Notify post authors when another member votes on their post.
- **Permission controls:** Limit who can view karma, cast votes, access rankings, or perform moderator actions using native phpBB permissions.

## Requirements

- **PHP:** 7.2.0 or higher
- **phpBB:** 3.3.0 or higher

## Installation

1. Download the extension repository.
2. Copy the files to your phpBB installation under `ext/vinny/karma/`:
   ```text
   phpBB/ext/vinny/karma/
   ```
3. Go to the **Admin Control Panel (ACP)** > **Customise** > **Extension Management** > **Manage extensions**.
4. Locate **Karma System** under **Disabled Extensions** and click **Enable**.

## Development & Linting

Frontend code quality is enforced with ESLint and Stylelint.

To install dependencies and run linters locally:

```bash
npm install
npm test
```

Automated extension tests run via GitHub Actions on every push and pull request.

## Support

[![Donate with Ko-fi](https://img.shields.io/badge/Donate-Ko--fi-29ABE2.svg)](https://ko-fi.com/vinny1)

## License

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](license.txt)
