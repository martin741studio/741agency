import { LLMProvider, LLMResult } from './provider.js';
import { Tool } from '../tools/registry.js';
import { MultimodalMessage } from './multimodal.js';
// OpenRouter uses standard OpenAI-like fetching for chat completions
import fetch from 'node-fetch';

export class OpenRouterProvider implements LLMProvider {
    private apiKey: string;
    private model: string;
    private history: any[] = [];
    private tools: Tool[];

    constructor(tools: Tool[], apiKey: string, model: string = 'openai/gpt-4o') {
        this.apiKey = apiKey;
        this.model = model;
        this.tools = tools;

        // System context
    }

    async sendMessage(message: string | any[] | MultimodalMessage, options?: { history?: any[] }): Promise<LLMResult> {
        let messages = options?.history ? [...options.history] : [
            {
                role: 'system',
                content: 'You are Gravity Claw, a helpful personal AI assistant. You run locally on my machine. Use tools when needed.'
            }
        ];

        if (typeof message === 'string') {
            messages.push({ role: 'user', content: message });
        } else if (Array.isArray(message)) {
            // Check if it's already a formatted messages array from executor
            if (message.length > 0 && (message[0] as any).role) {
                messages.push(...(message as any[]));
            } else {
                // This is likely a set of function responses or Gemini-style parts
                for (const item of message) {
                    if (item.functionResponse) {
                        messages.push({
                            role: 'tool',
                            tool_call_id: item.functionResponse.id || item.functionResponse.name,
                            content: typeof item.functionResponse.response.output === 'string'
                                ? item.functionResponse.response.output
                                : JSON.stringify(item.functionResponse.response.output)
                        });
                    } else if (item.text) { // Handle simple text parts if not a function response
                        messages.push({ role: 'user', content: item.text });
                    }
                    // Add more handling for other MultimodalMessage parts if necessary
                }
            }
        } else {
            // Assume MultimodalMessage is a complex object that needs to be added as a user message
            // This part might need refinement based on the actual structure of MultimodalMessage
            messages.push({ role: 'user', content: message });
        }

        // Map tools to OpenAI tool format
        const openAiTools = this.tools.map(tool => ({
            type: 'function',
            function: {
                name: tool.name,
                description: tool.description,
                parameters: tool.parameters
            }
        }));

        try {
            const response = await fetch('https://openrouter.ai/api/v1/chat/completions', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${this.apiKey}`,
                    'HTTP-Referer': 'https://github.com/martindrendel/741agency',
                    'X-Title': 'Gravity Claw',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    model: this.model,
                    messages: messages,
                    tools: openAiTools
                })
            });

            const data: any = await response.json();

            if (data.error) {
                throw new Error(`OpenRouter Error: ${data.error.message || JSON.stringify(data.error)}`);
            }

            const choice = data.choices[0];
            const assistantMsg = choice.message;

            // Interface adaptation for the existing loop in engine.ts
            return {
                response: {
                    text: () => assistantMsg.content || '',
                    candidates: [{
                        content: {
                            parts: assistantMsg.tool_calls ? assistantMsg.tool_calls.map((tc: any) => ({
                                functionCall: {
                                    name: tc.function.name,
                                    args: JSON.parse(tc.function.arguments),
                                    id: tc.id // Include ID for matching if needed
                                }
                            })) : [{ text: assistantMsg.content }]
                        }
                    }]
                },
                usage: data.usage ? {
                    promptTokens: data.usage.prompt_tokens,
                    completionTokens: data.usage.completion_tokens,
                    totalTokens: data.usage.total_tokens
                } : undefined,
                model: this.model
            };
        } catch (error: any) {
            console.error('[OpenRouter] Error:', error);
            throw error;
        }
    }
}

