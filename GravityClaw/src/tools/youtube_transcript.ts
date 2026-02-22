import { Tool } from './registry.js';
import { YoutubeTranscript } from 'youtube-transcript';

export const youtubeTranscriptTool: Tool = {
    name: 'fetch_youtube_transcript',
    description: 'Fetches the transcript of a YouTube video given its URL. Use this to summarize videos or answer questions about their content.',
    parameters: {
        type: 'object',
        properties: {
            url: {
                type: 'string',
                description: 'The full URL of the YouTube video.',
            },
        },
        required: ['url'],
    },
    execute: async ({ url }: { url: string }) => {
        try {
            console.log(`[Tool] Fetching transcript for: ${url}`);
            const transcript = await YoutubeTranscript.fetchTranscript(url);

            if (!transcript || transcript.length === 0) {
                return "No transcript available for this video.";
            }

            // Combine into a single string
            const fullText = transcript.map(item => item.text).join(' ');

            // Safety truncation for the tool output itself
            if (fullText.length > 20000) {
                return fullText.substring(0, 20000) + "... [TRANSCRIPT TRUNCATED]";
            }

            return fullText;
        } catch (error: any) {
            console.error('[Tool: YouTube] Error:', error.message);
            return `Error fetching transcript: ${error.message}`;
        }
    },
};
