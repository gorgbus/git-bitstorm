<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

require("../repo.php");

$css = ["repos", "issue"];

$issues = get_issues($db, $repo["id"], "open");

require("issues.phtml");
