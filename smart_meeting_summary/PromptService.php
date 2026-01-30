<?php

namespace SmartSummary;

class PromptService {
 
    public static function getMainPrompt() {
        return "Analyze the following meeting transcript and generate a structured summary. 
Return the response in valid JSON format with the following structure:
{
    \"agenda\": [
        \"Main topic 1\",
        \"Main topic 2\"
    ],
    \"key_decisions\": [
        {
            \"decision\": \"Decision made\",
            \"context\": \"Brief context\"
        }
    ],
    \"action_items\": [
        {
            \"task\": \"Specific task\",
            \"owner\": \"Person responsible\",
            \"due_date\": \"Due date if mentioned\"
        }
    ]
}
 
IMPORTANT GUIDELINES:
- Only include information explicitly mentioned in the transcript
- If no information is found for a category, return an empty array
- Do not invent or assume any details
- Keep responses factual and concise
- Extract exact names, dates, and responsibilities when available
- For action items, include both the task and who is responsible
- For decisions, include both the decision and the context/reasoning
 
Transcript:
";
    }
 
    public static function getSectionPrompt($section) {
        $prompts = [
            'agenda' => 'Extract only the main topics and agenda items from this meeting transcript. Focus on the primary discussion points and themes. Return as a JSON array of strings. Only include topics that were actually discussed.',
 
            'key_decisions' => 'Extract only the key decisions made in this meeting. Look for explicit statements of decisions, agreements, or conclusions. Return as a JSON array of objects with "decision" and "context" fields. Do not include discussions or suggestions that were not finalized as decisions.',
 
            'action_items' => 'Extract only action items with owners and due dates from this transcript. Look for tasks assigned to specific people. Return as a JSON array of objects with "task", "owner", and "due_date" fields. If a due date is not mentioned, set it to null or empty string.'
        ];
 
        return ($prompts[$section] ?? '') . "\n\nIMPORTANT: Only extract information explicitly stated in the transcript. Do not assume or invent any details.\n\nTranscript:\n";
    }
 
    public static function getValidationRules() {
        return [
            'agenda' => 'Should be an array of strings representing main discussion topics',
            'key_decisions' => 'Should be an array of objects with decision and context fields',
            'action_items' => 'Should be an array of objects with task, owner, and due_date fields'
        ];
    }
}