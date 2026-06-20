<?php
try {
    $pdo = new PDO('sqlite:database/cyberkavach.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get an existing event ID
    $stmt = $pdo->query("SELECT id FROM events LIMIT 1");
    $event = $stmt->fetch();
    $eventId = $event ? $event['id'] : 1;

    // Dummy Unsplash images
    $images = [
        'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?q=80&w=2070&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1555949963-ff9fe0c870eb?q=80&w=2070&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1614064641913-6b67ee83a54b?q=80&w=2070&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=2070&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?q=80&w=2070&auto=format&fit=crop'
    ];

    foreach ($images as $url) {
        $stmt = $pdo->prepare("INSERT INTO event_gallery (event_id, image_path, uploaded_by) VALUES (?, ?, ?)");
        $stmt->execute([$eventId, $url, 1]); // Assume user 1 is the uploader
    }

    echo "Seeded 5 dummy images into the global gallery successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
