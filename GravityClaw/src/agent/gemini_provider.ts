import { GoogleGenerativeAI, ChatSession, Part } from '@google/generative-ai';
import { LLMProvider, LLMResult } from './provider.js';
import { Tool } from '../tools/registry.js';
import { config } from '../config.js';
import { MultimodalMessage, isMultimodal } from './multimodal.js';

export class GeminiProvider implements LLMProvider {
    private chat: ChatSession;
    private model: any;
    private modelName: string;

    constructor(tools: Tool[], tier: 'flash' | 'pro' = 'flash') {
        const genAI = new GoogleGenerativeAI(config.geminiApiKey);

        const functionDeclarations = tools.map(tool => ({
            name: tool.name,
            description: tool.description,
            parameters: tool.parameters as any,
        }));

        this.modelName = tier === 'pro' ? 'gemini-2.0-pro-exp-02-05' : 'gemini-2.0-flash';

        const options: any = {
            model: this.modelName
        };

        if (functionDeclarations.length > 0) {
            options.tools = [{ functionDeclarations }];
        }

        this.model = genAI.getGenerativeModel(options);

        const initialHistory = [
            {
                role: 'user',
                parts: [{ text: 'You are Gravity Claw, a helpful personal AI assistant. You run locally on my machine. You have access to tools to help the user.' }],
            },
            {
                role: 'model',
                parts: [{ text: 'Understood. I am Gravity Claw. I am ready to help.' }],
            },
        ];

        this.chat = this.model.startChat({ history: initialHistory });
    }

    async sendMessage(message: string | any[] | MultimodalMessage): Promise<LLMResult> {
        let payload: string | Part[] | any[];

        if (isMultimodal(message)) {
            payload = message.map(part => {
                if (part.text) return { text: part.text };
                if (part.inlineData) return { inlineData: part.inlineData };
                return { text: '' }; // fallback
            });
        } else {
            payload = message as any;
        }

        try {
            const result = await this.chat.sendMessage(payload as any);
            const usage = (result.response as any)?.usageMetadata || null;

            return {
                response: {
                    text: () => result.response.text(),
                    candidates: result.response.candidates
                },
                usage: usage ? {
                    promptTokens: usage.promptTokenCount,
                    completionTokens: usage.candidatesTokenCount,
                    totalTokens: usage.totalTokenCount
                } : undefined,
                model: this.modelName
            };
        } catch (error: any) {
            console.error(`[Gemini:${this.modelName}] Error:`, error.message || error);
            throw error;
        }
    }
}
