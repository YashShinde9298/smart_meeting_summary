<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Meeting Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .editable-section {
            transition: all 0.3s ease;
        }
        .editable-section:hover {
            background-color: #f9fafb;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <header class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-clipboard-list text-blue-600 mr-3"></i>
                Smart Meeting Summary
            </h1>
            <p class="text-gray-600">Transform meeting transcripts into structured summaries</p>
        </header>
 
        <main>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                    Input Meeting Transcript
                </h2>
 
                <form id="summaryForm">
                    <div class="mb-4">
                        <label for="transcript" class="block text-sm font-medium text-gray-700 mb-2">
                            Meeting Transcript or Notes
                        </label>
                        <textarea 
                            id="transcript" 
                            name="transcript" 
                            rows="12" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Paste your meeting transcript or notes here...&#10;&#10;Example:&#10;John: Welcome everyone to today's meeting. Let's discuss the Q4 marketing strategy.&#10;Sarah: I think we should focus on social media campaigns.&#10;Mike: I agree. We need to allocate $50,000 for Facebook ads.&#10;John: Great. Sarah, can you prepare the campaign proposal by Friday?&#10;Sarah: Sure, I'll have it ready by EOD Friday.&#10;Mike: Also, we decided to postpone the product launch to January.&#10;John: Correct. The new launch date is January 15th."
                            required
                        ></textarea>
                    </div>
 
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            id="generateBtn"
                            class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition duration-200 font-medium"
                        >
                            <i class="fas fa-magic mr-2"></i>
                            Generate Summary
                        </button>
                    </div>
                </form>
            </div>
 
            <div id="loadingSection" class="hidden bg-white rounded-lg shadow-md p-8 mb-8">
                <div class="text-center">
                    <div class="loading-spinner mx-auto mb-4"></div>
                    <p class="text-gray-600">Generating your meeting summary...</p>
                </div>
            </div>
 
            <div id="resultsSection" class="hidden">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800">
                            <i class="fas fa-chart-line text-green-500 mr-2"></i>
                            Meeting Summary
                        </h2>
                        <button 
                            id="regenerateAllBtn"
                            class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200 text-sm"
                        >
                            <i class="fas fa-redo mr-1"></i>
                            Regenerate All
                        </button>
                    </div>
 
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="editable-section border rounded-lg p-4" data-section="agenda">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-semibold text-gray-700">
                                    <i class="fas fa-list text-blue-400 mr-2"></i>
                                    Agenda
                                </h3>
                                <button class="regenerate-section-btn text-blue-600 hover:text-blue-800 text-sm" data-section="agenda">
                                    <i class="fas fa-sync-alt mr-1"></i>
                                    Regenerate
                                </button>
                            </div>
                            <div id="agendaContent" class="space-y-2"></div>
                        </div>
 
                        <div class="editable-section border rounded-lg p-4" data-section="key_decisions">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-semibold text-gray-700">
                                    <i class="fas fa-gavel text-purple-400 mr-2"></i>
                                    Key Decisions
                                </h3>
                                <button class="regenerate-section-btn text-blue-600 hover:text-blue-800 text-sm" data-section="key_decisions">
                                    <i class="fas fa-sync-alt mr-1"></i>
                                    Regenerate
                                </button>
                            </div>
                            <div id="keyDecisionsContent" class="space-y-3"></div>
                        </div>
 
                        <div class="editable-section border rounded-lg p-4" data-section="action_items">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-semibold text-gray-700">
                                    <i class="fas fa-tasks text-green-400 mr-2"></i>
                                    Action Items
                                </h3>
                                <button class="regenerate-section-btn text-blue-600 hover:text-blue-800 text-sm" data-section="action_items">
                                    <i class="fas fa-sync-alt mr-1"></i>
                                    Regenerate
                                </button>
                            </div>
                            <div id="actionItemsContent" class="space-y-3"></div>
                        </div>
                    </div>
                </div>
 
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-download text-gray-600 mr-2"></i>
                        Export Options
                    </h3>
                    <div class="flex gap-4">
                        <button id="exportJsonBtn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                            <i class="fas fa-file-code mr-2"></i>
                            Export JSON
                        </button>
                        <button id="exportTextBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-file-alt mr-2"></i>
                            Export Text
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
 
    <script>
        let currentSummary = null;
        let currentTranscript = '';
 
        document.getElementById('summaryForm').addEventListener('submit', async function(e) {
            e.preventDefault();
 
            const transcript = document.getElementById('transcript').value;
            if (!transcript.trim()) {
                alert('Please enter a meeting transcript');
                return;
            }
 
            currentTranscript = transcript;
            await generateSummary();
        });
 
        async function generateSummary(section = null) {
            const loadingSection = document.getElementById('loadingSection');
            const resultsSection = document.getElementById('resultsSection');
 
            loadingSection.classList.remove('hidden');
            resultsSection.classList.add('hidden');
 
            try {
                const formData = new FormData();
                formData.append('transcript', currentTranscript);
                if (section) {
                    formData.append('section', section);
                }
 
                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData
                });
 
                const data = await response.json();
 
                if (data.success) {
                    if (section) {
                        currentSummary[section] = data.summary;
                    } else {
                        currentSummary = data.summary;
                    }
 
                    displaySummary();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while generating the summary');
            } finally {
                loadingSection.classList.add('hidden');
                resultsSection.classList.remove('hidden');
            }
        }
 
        function displaySummary() {
            if (!currentSummary) return;
 
            displayAgenda(currentSummary.agenda || []);
            displayKeyDecisions(currentSummary.key_decisions || []);
            displayActionItems(currentSummary.action_items || []);
        }
 
        function displayAgenda(agenda) {
            const content = document.getElementById('agendaContent');
            if (agenda.length === 0) {
                content.innerHTML = '<p class="text-gray-500 italic">No agenda items identified</p>';
                return;
            }
 
            content.innerHTML = agenda.map(item => 
                `<div class="flex items-start">
                    <i class="fas fa-chevron-right text-blue-400 mt-1 mr-2 text-sm"></i>
                    <span class="text-gray-700">${escapeHtml(item)}</span>
                </div>`
            ).join('');
        }
 
        function displayKeyDecisions(decisions) {
            const content = document.getElementById('keyDecisionsContent');
            if (decisions.length === 0) {
                content.innerHTML = '<p class="text-gray-500 italic">No key decisions identified</p>';
                return;
            }
 
            content.innerHTML = decisions.map(decision => 
                `<div class="border-l-4 border-purple-400 pl-3">
                    <p class="text-gray-800 font-medium">${escapeHtml(decision.decision)}</p>
                    ${decision.context ? `<p class="text-gray-600 text-sm mt-1">${escapeHtml(decision.context)}</p>` : ''}
                </div>`
            ).join('');
        }
 
        function displayActionItems(actionItems) {
            const content = document.getElementById('actionItemsContent');
            if (actionItems.length === 0) {
                content.innerHTML = '<p class="text-gray-500 italic">No action items identified</p>';
                return;
            }
 
            content.innerHTML = actionItems.map(item => 
                `<div class="border-l-4 border-green-400 pl-3">
                    <p class="text-gray-800 font-medium">${escapeHtml(item.task)}</p>
                    <div class="text-sm text-gray-600 mt-1">
                        ${item.owner ? `<span class="mr-3"><i class="fas fa-user mr-1"></i>${escapeHtml(item.owner)}</span>` : ''}
                        ${item.due_date ? `<span><i class="fas fa-calendar mr-1"></i>${escapeHtml(item.due_date)}</span>` : ''}
                    </div>
                </div>`
            ).join('');
        }
 
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
 
        document.querySelectorAll('.regenerate-section-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const section = this.dataset.section;
                generateSummary(section);
            });
        });
 
        document.getElementById('regenerateAllBtn').addEventListener('click', function() {
            generateSummary();
        });
 
        document.getElementById('exportJsonBtn').addEventListener('click', function() {
            if (!currentSummary) return;
 
            const dataStr = JSON.stringify(currentSummary, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
 
            const exportFileDefaultName = 'meeting-summary.json';
 
            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        });
 
        document.getElementById('exportTextBtn').addEventListener('click', function() {
            if (!currentSummary) return;
 
            let textContent = 'MEETING SUMMARY\n\n';
            textContent += 'AGENDA:\n';
            (currentSummary.agenda || []).forEach(item => {
                textContent += `• ${item}\n`;
            });
 
            textContent += '\nKEY DECISIONS:\n';
            (currentSummary.key_decisions || []).forEach(decision => {
                textContent += `• ${decision.decision}\n`;
                if (decision.context) {
                    textContent += `  Context: ${decision.context}\n`;
                }
            });
 
            textContent += '\nACTION ITEMS:\n';
            (currentSummary.action_items || []).forEach(item => {
                textContent += `• ${item.task}\n`;
                if (item.owner) textContent += `  Owner: ${item.owner}\n`;
                if (item.due_date) textContent += `  Due: ${item.due_date}\n`;
            });
 
            const dataUri = 'data:text/plain;charset=utf-8,'+ encodeURIComponent(textContent);
            const exportFileDefaultName = 'meeting-summary.txt';
 
            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        });
    </script>
</body>
</html>