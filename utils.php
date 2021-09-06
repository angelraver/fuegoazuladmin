<?php
function orNull($val) {
  return (empty($val)) ? NULL : htmlspecialchars(strip_tags($val));
};
