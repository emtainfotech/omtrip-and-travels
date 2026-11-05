<?php
$file = __DIR__ . '/../app/Views/home/index.php';
$content = file_get_contents($file);

// Find the modal block
$modalPattern = '/<!-- 11\. QUICK QUOTE CONTACT MODAL -->.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/s';
preg_match($modalPattern, $content, $matches);
$modalHtml = $matches[0] ?? '';

// Remove all occurrences of the modal
$cleaned = preg_replace($modalPattern, '', $content);

// Clean up extra whitespace/newlines
$cleaned = trim($cleaned);

// Append the modal exactly once at the end
if ($modalHtml) {
    $cleaned .= "\n\n" . $modalHtml . "\n";
}

file_put_contents($file, $cleaned);
echo "✅ Cleaned home index view successfully!\n";

