<?php

function error_404() {
    http_response_code(404);
    echo '404 not found';
    exit;
}

function h($str) {
  return htmlspecialchars($str);
}
