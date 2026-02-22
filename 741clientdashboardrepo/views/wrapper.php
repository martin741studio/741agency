<?php
// views/wrapper.php
// $report_slug is passed from index.php

$file_path = __DIR__ . '/../clients/' . $report_slug;

if (!file_exists($file_path)) {
    die("Report not found: " . htmlspecialchars($report_slug));
}

// 1. Get HTML Content
$html = file_get_contents($file_path);

// 2. Fetch Comments (Correct Placement)
$db = getDB();
$stmt = $db->prepare("
    SELECT c.*, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE report_slug = ? 
    ORDER BY created_at ASC
");
$stmt->execute([$report_slug]);
$comments_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Prepare Comment System Injection
ob_start();
?>
<!-- INJECTED COMMENT SYSTEM -->
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;700&family=Montserrat:wght@400;600&display=swap"
    rel="stylesheet">
<style>
    #feedback-trigger {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #000;
        color: #FCB141;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5);
        cursor: pointer;
        z-index: 9999;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 2px solid #FCB141;
    }

    /* Fix Report Overlap */
    body {
        padding-top: 80px !important; /* Ensure content starts below fixed nav */
    }

    /* Top Navigation Bar */
    #portal-nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 70px;
        background: #fff;
        border-bottom: 2px solid #FCB141;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 40px;
        z-index: 10001; /* High Z-index */
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        font-family: 'Montserrat', sans-serif;
    }

    #portal-nav .nav-left img {
        height: 35px;
    }

    #portal-nav .nav-right {
        display: flex;
        gap: 25px;
    }

    #portal-nav .nav-right a {
        text-decoration: none;
        color: #000;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        transition: color 0.2s;
        border-bottom: 2px solid transparent;
    }

    #portal-nav .nav-right a:hover {
        color: #FCB141;
        border-color: #FCB141;
    }

    #feedback-trigger:hover {
        transform: scale(1.1);
        box-shadow: 0 0 20px rgba(252, 177, 65, 0.4);
    }

    #feedback-panel {
        position: fixed;
        top: 0;
        right: -400px;
        width: 350px;
        height: 100vh;
        background: #111;
        color: white;
        box-shadow: -5px 0 35px rgba(0, 0, 0, 0.8);
        z-index: 10000;
        transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        font-family: 'Montserrat', sans-serif;
        border-left: 2px solid #FCB141;
    }

    #feedback-panel.open {
        right: 0;
    }

    #feedback-header {
        padding: 20px;
        background: #000;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #333;
    }

    #feedback-header h3 {
        font-family: 'Jost', sans-serif;
        color: #FCB141;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    #feedback-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #1a1a1a;
    }

    #feedback-footer {
        padding: 20px;
        background: #000;
        border-top: 1px solid #333;
    }

    /* Comment Styling */
    .comment-item {
        background: #252525;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 15px;
        border-left: 3px solid #FCB141;
    }

    .comment-user {
        font-family: 'Jost', sans-serif;
        font-weight: 700;
        font-size: 0.8em;
        color: #FCB141;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .comment-text {
        font-size: 0.9em;
        line-height: 1.5;
        color: #eee;
    }

    .comment-date {
        font-size: 0.7em;
        color: #666;
        text-align: right;
        margin-top: 8px;
    }

    /* Input Styling */
    #comment-input {
        width: 100%;
        height: 70px;
        background: #222;
        color: white;
        border: 1px solid #444;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 12px;
        font-family: 'Montserrat', sans-serif;
        resize: none;
    }

    #comment-input:focus {
        outline: none;
        border-color: #FCB141;
        background: #2a2a2a;
    }

    .btn-send {
        width: 100%;
        background: #FCB141;
        color: black;
        border: none;
        padding: 12px;
        border-radius: 50px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        font-family: 'Jost', sans-serif;
        transition: transform 0.2s;
    }

    .btn-send:hover {
        transform: translateY(-2px);
        background: #ffc107;
    }

    .btn-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<div id="feedback-trigger" onclick="toggleFeedback()">
    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path
            d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
        </path>
    </svg>
</div>
<!-- Top Navigation Bar -->
<div id="portal-nav">
    <div class="nav-left">
        <img src="https://741.studio/wp-content/uploads/2022/09/741studio-03-1-1.svg" alt="741 Studio">
    </div>
    <div class="nav-right">
        <a href="index.php?route=dashboard">DASHBOARD</a>
        <a href="index.php?route=profile">PROFILE</a>
        <a href="index.php?route=logout">LOGOUT</a>
    </div>
</div>

<div id="feedback-panel">
    <div id="feedback-header">
        <h3 style="margin:0; font-size:1.1rem; font-weight:bold;">Project Feedback</h3>
        <button onclick="toggleFeedback()"
            style="background:none; border:none; color:white; font-size:1.5rem; cursor:pointer;">&times;</button>
    </div>
    <div id="feedback-body">
        <div style="text-align:center; color:#666; margin-top:40px; font-size:0.9em;">Loading conversation...</div>
    </div>
    <div id="feedback-footer">
        <textarea id="comment-input" placeholder="Feedback or request..."></textarea>
        <button class="btn-send" onclick="submitComment()">Send to 741</button>
    </div>
</div>

<script>
    const reportSlug = <?= json_encode($report_slug) ?>;
    const csrfToken = <?= json_encode($_SESSION['csrf_token']) ?>;

    function toggleFeedback() {
        const panel = document.getElementById('feedback-panel');
        // if (!panel.classList.contains('open')) {
        //     loadComments();
        // }
        panel.classList.toggle('open');
    }

    function submitComment() {
        const input = document.getElementById('comment-input');
        const content = input.value.trim();
        if (!content) return;

        const btn = document.querySelector('.btn-send');
        const originalText = btn.innerText;
        btn.innerText = 'Sending...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('content', content);
        formData.append('report_slug', reportSlug);
        formData.append('csrf_token', csrfToken);

        fetch('index.php?route=api/comment', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    input.value = '';
                    // Reload comments (or append locally)
                    addCommentToUI({
                        username: 'Me', // We could pass real user via PHP
                        content: content,
                        created_at: 'Just now'
                    });
                }
                btn.innerText = originalText;
                btn.disabled = false;
            })
            .catch(err => {
                alert('Error sending comment.');
                btn.innerText = originalText;
                btn.disabled = false;
            });
    }

    function addCommentToUI(c) {
        const container = document.getElementById('feedback-body');
        // Clear loading state if present
        if (container.innerText.includes('Loading')) container.innerHTML = '';
        if (container.innerText.includes('Start the conversation')) container.innerHTML = '';

        const div = document.createElement('div');
        div.className = 'comment-item';
        div.innerHTML = `
            <div class="comment-user">${c.username}</div>
            <div class="comment-text">${c.content.replace(/\n/g, '<br>')}</div>
            <div class="comment-date">${c.created_at}</div>
        `;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    // INITIAL LOAD
    const existingComments = <?= json_encode($comments_data) ?>;
    document.getElementById('feedback-body').innerHTML = ''; // Clear loading

    if (existingComments.length === 0) {
        document.getElementById('feedback-body').innerHTML = '<div style="text-align:center; color:#555; margin-top:40px; font-style:italic;">No comments yet.<br>Start the conversation!</div>';
    } else {
        existingComments.forEach(addCommentToUI);
    }
</script>
<!-- END INJECTED SYSTEM -->
<?php
$injection = ob_get_clean();

// 4. Inject
// Inject Favicon into Head if possible
if (strpos($html, '</head>') !== false) {
    $favicon = '<link rel="icon" href="/assets/images/favicon.jpg" type="image/jpeg">';
    $html = str_replace('</head>', $favicon . '</head>', $html);
}

// Inject Comment System into Body
if (strpos($html, '</body>') !== false) {
    $html = str_replace('</body>', $injection . '</body>', $html);
} else {
    // Fallback: Append to end
    $html .= $injection;
}

echo $html;
?>