<?php
try {
    $pdo = new PDO('sqlite:database/cyberkavach.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("
    CREATE TABLE IF NOT EXISTS events (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        event_date DATETIME NOT NULL,
        status VARCHAR(50) DEFAULT 'upcoming',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("
    CREATE TABLE IF NOT EXISTS event_registrations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        event_id INTEGER NOT NULL,
        registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(user_id) REFERENCES users(id),
        FOREIGN KEY(event_id) REFERENCES events(id)
    )");

    // Insert dummy events
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM events");
    $count = $stmt->fetch()['count'];
    if ($count == 0) {
        $pdo->exec("INSERT INTO events (title, description, event_date, status) VALUES 
        ('Zero Day Chase Hackathon', 'Intense 24-hour CTF competition. Find vulnerabilities and exploit them.', '2026-12-24 12:00:00', 'upcoming'),
        ('Web Exploitation Workshop', 'Learn SQLi, XSS, and CSRF fundamentals with hands-on labs.', '2026-07-15 10:00:00', 'upcoming'),
        ('Reverse Engineering 101', 'Introduction to assembly and ghidra.', '2026-05-10 14:00:00', 'completed')");
    }

    echo "Phase 4 migrations completed successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
