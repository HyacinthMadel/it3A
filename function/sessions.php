<?php

function startUserSession(PDO $pdo): void
{
    $stmt = $pdo->prepare("
        INSERT INTO user_sessions (user_id, session_start)
        VALUES (?, CURRENT_TIMESTAMP)
    ");

    $stmt->execute([$_SESSION['user_id']]);

    $_SESSION['session_id'] = (int) $pdo->lastInsertId();
}

function endUserSession(PDO $pdo): void
{
    if (!isset($_SESSION['session_id'])) {
        return;
    }

    $stmt = $pdo->prepare("
        UPDATE user_sessions
        SET
            session_end = CURRENT_TIMESTAMP,
            session_duration = TIMESTAMPDIFF(
                SECOND,
                session_start,
                CURRENT_TIMESTAMP
            )
        WHERE session_id = ?
          AND user_id = ?
          AND session_end IS NULL
    ");

    $stmt->execute([
        $_SESSION['session_id'],
        $_SESSION['user_id']
    ]);

    unset($_SESSION['session_id']);
}