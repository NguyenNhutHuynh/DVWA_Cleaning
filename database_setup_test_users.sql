-- Insert test admin user
-- Email: admin@cleaning.local
-- Password: admin123

INSERT INTO users (name, email, phone, address, password_hash, password, role, approval_status, created_at)
VALUES (
    'Admin User',
    'admin@cleaning.local',
    '0987654321',
    'Admin Office',
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHqZ66',  -- password_hash for 'admin123'
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHqZ66',  -- password field
    'admin',
    'active',
    NOW()
);

-- Insert test customer user
INSERT INTO users (name, email, phone, address, password_hash, password, role, approval_status, created_at)
VALUES (
    'John Customer',
    'customer@cleaning.local',
    '0123456789',
    'Customer Address',
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHqZ66',  -- password_hash for 'customer123'
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHqZ66',
    'customer',
    'active',
    NOW()
);

-- Insert test worker user (pending approval)
INSERT INTO users (name, email, phone, address, password_hash, password, role, approval_status, created_at)
VALUES (
    'Jane Worker',
    'worker@cleaning.local',
    '0111111111',
    'Worker Address',
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHqZ66',  -- password_hash for 'worker123'
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHqZ66',
    'worker',
    'active',
    NOW()
);

-- Test account credentials:
-- admin@cleaning.local / admin123
-- customer@cleaning.local / customer123
-- worker@cleaning.local / worker123
