<?php

@mkdir('/tmp/views', 0755, true);
@mkdir('/tmp/cache', 0755, true);

// Pintu masuk Vercel: semua permintaan diteruskan ke front controller Laravel.
require __DIR__ . '/../public/index.php';
