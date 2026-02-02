const { YoutubeTranscript } = require('youtube-transcript');

async function run() {
    const url = process.argv[2];
    if (!url) {
        console.error("Usage: node youtube.js <video-url>");
        process.exit(1);
    }

    try {
        const transcript = await YoutubeTranscript.fetchTranscript(url);
        // Combine into a single string for valid output
        const fullText = transcript.map(item => item.text).join(' ');
        console.log(fullText);
    } catch (error) {
        console.error("Error fetching transcript:", error.message);
        process.exit(1);
    }
}

run();
