<?php
function hound_get_post(int $id, string $type, string $slug, string $template = ''): array {
    try {
        $db = new PDO('sqlite:' . HOUND_DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $where = '';
        $found_post = [];

        // Build the WHERE clause based on the parameters passed
        if ((int) $id > 0) {
            $where .= ' AND post_id = :post_id ';
        }

        if ((string) $type !== '') {
            $where .= ' AND post_type = :post_type ';
        }

        if ((string) $slug !== '') {
            $where .= ' AND post_slug = :post_slug ';
        }

        if ((string) $template !== '') {
            $where .= ' AND post_template = :post_template ';
        }

        $stmt = $db->prepare('SELECT * FROM posts WHERE 1 = 1 ' . $where);

        // Bind the parameters to the query based on the parameters passed
        if ((int) $id > 0) {
            $stmt->bindParam(':post_id', $id);
        }

        if ((string) $type !== '') {
            $stmt->bindParam(':post_type', $type);
        }

        if ((string) $slug !== '') {
            $stmt->bindParam(':post_slug', $slug);
        }

        if ((string) $template !== '') {
            $post_template = $template . '.php';
            $stmt->bindParam(':post_template', $post_template);
        }

        $stmt->execute();

        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            $found_post['post_id'] = $post['post_id'];
            $found_post['post_slug'] = $post['post_slug'];
            $found_post['post_title'] = $post['post_title'];
            $found_post['post_type'] = $post['post_type'];
            $found_post['post_content'] = $post['post_content'];
            $found_post['post_template'] = $post['post_template'];
            $found_post['post_date'] = $post['post_date'];
            $found_post['post_author'] = $post['post_author'];

            return $found_post;
        } else {
            echo 'Post not found.';

            return [];
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();

        return [];
    }
}

function hound_get_posts(string $type): array {
    try {
        $db = new PDO('sqlite:' . HOUND_DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM posts WHERE post_type = :post_type');

        $stmt->bindParam(':post_type', $type);
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $posts;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();

        return [];
    }
}

function hound_insert_post(array $post_array): void {
    // Check if $post_array is actually an array (this is redundant with type declarations, but shown for completeness)
    if (!is_array($post_array)) {
        throw new InvalidArgumentException('Expected parameter to be an array.');
    }

    try {
        $db = new PDO('sqlite:' . HOUND_DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $post_type = $post_array['type'];
        $post_slug = $post_array['slug'];
        $post_title = $post_array['title'];
        $post_content = $post_array['content'];
        $post_template = $post_array['template'];
        $post_author = $post_array['author'];

        // Generate a unique post_slug
        $unique_slug = generate_new_slug($db, $post_slug);

        // Insert the post into the database
        $stmt = $db->prepare('
            INSERT INTO posts (post_type, post_slug, post_title, post_content, post_template, post_author)
            VALUES (:post_type, :post_slug, :post_title, :post_content, :post_template, :post_author)
        ');

        $stmt->bindParam(':post_type', $post_type);
        $stmt->bindParam(':post_slug', $unique_slug);
        $stmt->bindParam(':post_title', $post_title);
        $stmt->bindParam(':post_content', $post_content);
        $stmt->bindParam(':post_template', $post_template);
        $stmt->bindParam(':post_author', $post_author);

        $stmt->execute();

        echo 'Blog post inserted successfully.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function hound_update_post(array $post_array): bool {
    // Check if $post_array is actually an array (this is redundant with type declarations, but shown for completeness)
    if (!is_array($post_array)) {
        throw new InvalidArgumentException('Expected parameter to be an array.');
    }

    try {
        $db = new PDO('sqlite:' . HOUND_DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sample data for a blog post
        $post_id = $post_array['post_id'];
        $post_type = $post_array['type'];
        $post_slug = $post_array['slug'];
        $post_title = $post_array['title'];
        $post_content = $post_array['content'];
        $post_template = $post_array['template'];
        $post_author = $post_array['author'];

        // Check if the slug needs to be unique
        $unique_slug = generate_updated_slug($db, $post_slug, $post_id);

        // Update the post in the database
        $stmt = $db->prepare('
            UPDATE posts
            SET post_type = :post_type,
                post_slug = :post_slug,
                post_title = :post_title,
                post_content = :post_content,
                post_template = :post_template,
                post_author = :post_author
            WHERE post_id = :post_id
        ');

        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':post_type', $post_type);
        $stmt->bindParam(':post_slug', $unique_slug);
        $stmt->bindParam(':post_title', $post_title);
        $stmt->bindParam(':post_content', $post_content);
        $stmt->bindParam(':post_template', $post_template);
        $stmt->bindParam(':post_author', $post_author);

        $stmt->execute();

        echo 'Blog post inserted successfully.';

        return true;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();

        return false;
    }
}

function hound_delete_post(int $post_id): bool {
    try {
        $db = new PDO('sqlite:' . HOUND_DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('DELETE FROM posts WHERE post_id = :post_id');
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0; // Return true if a row was deleted
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
        return false;
    }
}

function hound_clone_post(int $post_id): bool {
    try {
        $db = new PDO('sqlite:' . HOUND_DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Step 1: Retrieve the original post data
        $stmt = $db->prepare('SELECT * FROM posts WHERE post_id = :post_id');
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            // Post not found
            return false;
        }

        // Step 2: Generate a unique post_slug
        $new_slug = generate_unique_slug($db, $post['post_slug']);

        // Step 3: Insert the new post with the retrieved data, excluding post_id
        $stmt = $db->prepare('
            INSERT INTO posts (post_type, post_slug, post_title, post_content, post_template, post_author)
            VALUES (:post_type, :post_slug, :post_title, :post_content, :post_template, :post_author)
        ');

        $stmt->bindParam(':post_type', $post['post_type']);
        $stmt->bindParam(':post_slug', $new_slug);
        $stmt->bindParam(':post_title', $post['post_title']);
        $stmt->bindParam(':post_content', $post['post_content']);
        $stmt->bindParam(':post_template', $post['post_template']);
        $stmt->bindParam(':post_author', $post['post_author']);

        $stmt->execute();
        return true; // Cloning was successful
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
        return false; // Cloning failed due to a database error
    }
}

function generate_unique_slug(PDO $db, string $original_slug): string {
    $new_slug = $original_slug . '-copy';
    $suffix = 1;

    $stmt = $db->prepare('SELECT COUNT(*) FROM posts WHERE post_slug = :post_slug');
    $stmt->bindParam(':post_slug', $new_slug, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    while ($count > 0) {
        $new_slug = $original_slug . '-copy-' . $suffix;
        $stmt->bindParam(':post_slug', $new_slug, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $suffix++;
    }

    return $new_slug;
}

function generate_new_slug(PDO $db, string $original_slug): string {
    $new_slug = $original_slug;
    $suffix = 1;

    $stmt = $db->prepare('SELECT COUNT(*) FROM posts WHERE post_slug = :post_slug');
    $stmt->bindParam(':post_slug', $new_slug, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    while ($count > 0) {
        $new_slug = $original_slug . '-' . $suffix;
        $stmt->bindParam(':post_slug', $new_slug, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $suffix++;
    }

    return $new_slug;
}

function generate_updated_slug(PDO $db, string $original_slug, int $post_id = null): string {
    $new_slug = $original_slug;
    $suffix = 1;

    $stmt = $db->prepare('SELECT COUNT(*) FROM posts WHERE post_slug = :post_slug AND post_id != :post_id');
    $stmt->bindParam(':post_slug', $new_slug, PDO::PARAM_STR);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    while ($count > 0) {
        $new_slug = $original_slug . '-' . $suffix;
        $stmt->bindParam(':post_slug', $new_slug, PDO::PARAM_STR);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $suffix++;
    }

    return $new_slug;
}
