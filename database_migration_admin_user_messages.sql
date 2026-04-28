-- ============================================================================
-- CREATE TABLE ADMIN_USER_MESSAGES
-- ============================================================================

CREATE TABLE IF NOT EXISTS admin_user_messages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    sender_id BIGINT UNSIGNED NOT NULL,
    sender_role ENUM('admin','customer','worker') NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_admin_user_messages_user (user_id),
    KEY idx_admin_user_messages_sender (sender_id),
    CONSTRAINT fk_admin_user_messages_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_admin_user_messages_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Direct messages between admin and users';

-- ============================================================================
-- VERIFY TABLE CREATED
-- ============================================================================
-- DESCRIBE admin_user_messages;
