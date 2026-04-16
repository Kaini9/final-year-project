<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <!-- Header -->
        <div class="bg-ink text-white py-6 shadow-lg">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <div>
                    <h1 class="font-display text-2xl uppercase tracking-widest">CV Preview</h1>
                    <p class="text-gray-300 text-sm mt-2">{{ $jobApplication->user->name }} — {{ $jobApplication->job->title }}</p>
                </div>
                <a href="javascript:history.back()" class="px-4 py-2 bg-white text-ink font-bold text-sm uppercase tracking-widest rounded hover:bg-gray-100 transition-colors">
                    ← Back
                </a>
            </div>
        </div>

        <!-- PDF Viewer -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <!-- Toolbar -->
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <button id="prev-page" class="px-3 py-2 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <div class="flex items-center gap-2">
                            <span id="page-num" class="text-sm font-semibold text-gray-700">1</span>
                            <span class="text-gray-500">/</span>
                            <span id="page-count" class="text-sm font-semibold text-gray-700">-</span>
                        </div>
                        <button id="next-page" class="px-3 py-2 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-3">
                        <button id="zoom-out" class="px-3 py-2 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors" title="Zoom Out">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
                        </button>
                        <span id="zoom-level" class="text-sm font-semibold text-gray-700 min-w-[60px] text-center">100%</span>
                        <button id="zoom-in" class="px-3 py-2 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors" title="Zoom In">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path></svg>
                        </button>
                        <a href="{{ $cvUrl }}" download="{{ $fileName }}" class="px-4 py-2 bg-ink text-white font-bold text-sm uppercase tracking-widest rounded hover:bg-gray-800 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download
                        </a>
                    </div>
                </div>

                <!-- PDF Canvas -->
                <div class="bg-gray-200 p-6 min-h-[600px] flex items-center justify-center overflow-auto" id="pdf-container">
                    <canvas id="pdf-canvas" class="bg-white shadow-lg"></canvas>
                </div>

                <!-- Loading indicator -->
                <div id="loading" class="hidden absolute inset-0 flex items-center justify-center bg-black/20">
                    <div class="bg-white px-6 py-4 rounded-lg shadow-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-ink animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span class="text-sm font-semibold text-gray-700">Loading PDF...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Info -->
            <div class="mt-6 bg-white p-4 rounded-xl border border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Applicant</p>
                        <p class="text-sm font-semibold text-ink mt-1">{{ $jobApplication->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Job Title</p>
                        <p class="text-sm font-semibold text-ink mt-1">{{ $jobApplication->job->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Submitted</p>
                        <p class="text-sm font-semibold text-ink mt-1">{{ $jobApplication->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // Set up PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const url = '{{ $cvUrl }}';
        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1;

        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const pageNum_span = document.getElementById('page-num');
        const pageCount_span = document.getElementById('page-count');
        const zoomLevel_span = document.getElementById('zoom-level');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');
        const container = document.getElementById('pdf-container');

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                const renderTask = page.render(renderContext);
                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                    updateButtons();
                });
            });

            pageNum_span.textContent = num;
        }

        function queueRenderPage(num) {
            if (num < 1 || num > pdfDoc.numPages) {
                return;
            }
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function prevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        function nextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        function updateButtons() {
            prevBtn.disabled = pageNum <= 1;
            nextBtn.disabled = pageNum >= pdfDoc.numPages;
        }

        function zoomIn() {
            if (scale < 3) {
                scale += 0.25;
                zoomLevel_span.textContent = Math.round(scale * 100) + '%';
                queueRenderPage(pageNum);
            }
        }

        function zoomOut() {
            if (scale > 0.5) {
                scale -= 0.25;
                zoomLevel_span.textContent = Math.round(scale * 100) + '%';
                queueRenderPage(pageNum);
            }
        }

        prevBtn.addEventListener('click', prevPage);
        nextBtn.addEventListener('click', nextPage);
        document.getElementById('zoom-in').addEventListener('click', zoomIn);
        document.getElementById('zoom-out').addEventListener('click', zoomOut);

        // Load PDF
        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            pdfDoc = pdf;
            pageCount_span.textContent = pdf.numPages;
            renderPage(pageNum);
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
            canvas.style.display = 'none';
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-600 font-semibold">Error loading PDF</p>
                    <p class="text-sm text-gray-500 mt-2">Try <a href="{{ $cvUrl }}" download class="font-bold text-ink hover:underline">downloading</a> instead</p>
                </div>
            `;
        });
    </script>
</x-app-layout>
