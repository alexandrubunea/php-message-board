<?php

function getLatestImages(PDO $conn): array
{
    $sql_command = "SELECT image_path, created_at FROM messages WHERE image_path != '(null)' ORDER BY created_at DESC LIMIT 10";
    $result_arr = [];

    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute();

        if($stmt->rowCount() == 0)
            return $result_arr;

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $result = [];

            $result['image_path'] = $row['image_path'];
            $result['created_at'] = $row['created_at'];

            $result_arr[] = $result;
        }

    } catch(PDOException $e) {
        error_log($e->getMessage());
        return $result_arr;
    }

    return $result_arr;
}

function getLatestComments(PDO $conn): array
{
    $sql_command = "
            SELECT
                c.comment_id,
                c.message_id,
                c.created_at,
                c.content,
                u.username AS author,
                m.title AS message_title,
                COUNT(l) AS number_of_likes
            FROM comments c
            JOIN users u ON c.author_id = u.user_id
            LEFT JOIN likes l ON c.comment_id = l.comment_id
            LEFT JOIN messages m ON c.message_id = m.message_id
            GROUP BY c.comment_id, c.message_id, c.created_at, u.username, m.title
            ORDER BY c.created_at DESC LIMIT 20;
    ";
    $result_arr = [];

    $stmt = $conn->prepare($sql_command);
    try {
        $stmt->execute();
        if($stmt->rowCount() == 0)
            return $result_arr;

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $result = [];

            $result['comment_id'] = $row['comment_id'];
            $result['message_id'] = $row['message_id'];
            $result['content'] = substr(strip_tags($row['content']), 0, 200);
            $result['message_title'] = substr(strip_tags($row['message_title']), 0, 30);
            $result['author'] = $row['author'];
            $result['likes'] = $row['number_of_likes'];

            $result_arr[] = $result;
        }
    } catch(PDOException $e) {
        error_log($e->getMessage());
        return $result_arr;
    }
    return $result_arr;
}

function getLatestUsers(PDO $conn): array
{
    $sql_command = "
            SELECT
                username,
                created_at
            FROM users
            ORDER BY created_at DESC LIMIT 20;
    ";
    $result_arr = [];

    $stmt = $conn->prepare($sql_command);
    try {
        $stmt->execute();
        if($stmt->rowCount() == 0)
            return $result_arr;

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $result = [];

            $result['username'] = $row['username'];

            $formatted_date = "";
            try {
                $formatted_date = (new DateTime($row['created_at']))->format('d F Y H:i');
            } catch (DateMalformedStringException) {
                echo "Something went wrong.";
            }
            $result['created_at'] = $formatted_date;

            $result_arr[] = $result;
        }
    } catch(PDOException $e) {
        error_log($e->getMessage());
        return $result_arr;
    }
    return $result_arr;
}

function getHottestMessages(PDO $conn): array
{
    $sql_command = "
        SELECT
            m.message_id,
            m.title,
            u.username AS author,
            m.created_at,
            COUNT(l2) AS number_of_likes,
            (COUNT(l2) - EXTRACT(EPOCH FROM (CURRENT_TIMESTAMP - m.created_at)) / 3600) AS score
            FROM messages m
                JOIN users u ON m.author = u.user_id
                LEFT JOIN likes l2 ON l2.message_id = m.message_id
            GROUP BY
                m.message_id, u.username, m.title, m.content, m.image_path, m.created_at
            ORDER BY
                score DESC
            LIMIT 20;";
    $result_arr = [];

    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute();

        if($stmt->rowCount() == 0)
            return $result_arr;

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $result = [];

            $result['message_id'] = $row['message_id'];
            $result['title'] = $row['title'];
            $result['author'] = $row['author'];
            $result['likes'] = $row['number_of_likes'];

            $formatted_date = "";
            try {
                $formatted_date = (new DateTime($row['created_at']))->format('d F Y H:i');
            } catch (DateMalformedStringException) {
                echo "Something went wrong.";
            }

            $result['created_at'] = $formatted_date;

            $result_arr[] = $result;
        }

    } catch(PDOException $e) {
        error_log($e->getMessage());
        return $result_arr;
    }

    return $result_arr;
}