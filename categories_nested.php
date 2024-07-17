<?php
$mysqli = new mysqli("localhost", "root", "", "dm");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT id, category_name, parent_id FROM categories";
$result = $mysqli->query($sql);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$mysqli->close();

function buildCategoryTree(array $elements, $parentId = 0) {
    $branch = [];

    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = buildCategoryTree($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

$categoryTree = buildCategoryTree($categories); // $categories from step 1


function renderCategoryTree($categoryTree) {
    echo '<ul>';
    foreach ($categoryTree as $category) {
        echo '<li>' . $category['category_name'];
        if (!empty($category['children'])) {
            renderCategoryTree($category['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
}

renderCategoryTree($categoryTree); // $categoryTree from step 2
?>