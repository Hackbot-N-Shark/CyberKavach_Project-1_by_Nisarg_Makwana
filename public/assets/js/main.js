document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS Animations
    if (typeof AOS !== 'undefined') {
        AOS.init({ once: true, offset: 50 });
    }

    const pageId = document.body.dataset.page || 'home';

    // --- WEB AUDIO API FOR SYNTHESIZED HACKER SOUNDS ---
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    let audioCtx = null;
    let audioInitialized = false;

    function initAudio() {
        if (!audioInitialized && AudioContext) {
            audioCtx = new AudioContext();
            audioInitialized = true;
            playBootSound();
        }
    }
    window.addEventListener('click', initAudio, { once: true });
    window.addEventListener('keydown', initAudio, { once: true });

    function playHoverSound() {
        if (!audioCtx) return;
        const osc = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        osc.type = 'square';
        osc.frequency.setValueAtTime(800, audioCtx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(1200, audioCtx.currentTime + 0.05);
        gainNode.gain.setValueAtTime(0.05, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.05);
        osc.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 0.05);
    }

    function playTypingSound() {
        if (!audioCtx) return;
        const osc = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        osc.type = 'sawtooth';
        osc.frequency.setValueAtTime(100 + Math.random() * 500, audioCtx.currentTime);
        gainNode.gain.setValueAtTime(0.02, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.02);
        osc.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 0.02);
    }

    function playBootSound() {
        if (!audioCtx) return;
        const osc = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(50, audioCtx.currentTime);
        osc.frequency.linearRampToValueAtTime(200, audioCtx.currentTime + 1.5);
        gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
        gainNode.gain.linearRampToValueAtTime(0.1, audioCtx.currentTime + 0.5);
        gainNode.gain.linearRampToValueAtTime(0, audioCtx.currentTime + 1.5);
        osc.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 1.5);
    }

    const interactiveElements = document.querySelectorAll('.btn-hacker, .nav-link');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', playHoverSound);
    });

    // Terminal Boot Sequence (Loading Screen)
    const loadingScreen = document.getElementById('loading-screen');
    const bootText = document.getElementById('boot-text');
    
    if (loadingScreen && bootText) {
        
        const bootSequences = {
            'home': [
                "[OK] Kernel loaded.",
                "[OK] Mounting root file system...",
                "[OK] Initiating CyberKavach mainframe...",
                "[OK] Bypassing firewall protocols...",
                "[OK] Establishing secure connection...",
                "ACCESS GRANTED."
            ],
            'about': [
                "[OK] Authenticating credentials...",
                "[OK] Decrypting history logs...",
                "[OK] Accessing organization archive...",
                "[OK] Retrieving mission objectives...",
                "ARCHIVE OPENED."
            ],
            'community': [
                "[OK] Scanning network nodes...",
                "[OK] Establishing P2P swarm tunnels...",
                "[OK] Synchronizing training databases...",
                "[OK] Fetching operative activity...",
                "SWARM CONNECTED."
            ],
            'team': [
                "[OK] Bypassing clearance level 5...",
                "[OK] Accessing roster database...",
                "[OK] Downloading operative profiles...",
                "[OK] Rendering identity matrices...",
                "PROFILES LOADED."
            ]
        };

        const loaders = {
            'home': `<div class="spinner-border text-danger" role="status" style="width: 3rem; height: 3rem;"><span class="visually-hidden">Loading...</span></div>`,
            'about': `<div class="progress rounded-0 border border-danger bg-black" style="height: 15px;">
                          <div class="progress-bar bg-danger" id="about-progress" style="width: 0%; transition: width 0.2s;"></div>
                      </div>`,
            'community': `<div class="radar-loader"></div>`,
            'team': `<div class="cyber-scan-container w-100"><div class="cyber-scan"></div></div>`
        };

        const bootAnimation = document.getElementById('boot-animation');
        if (bootAnimation) {
            bootAnimation.innerHTML = loaders[pageId] || loaders['home'];
        }

        const bootMessages = bootSequences[pageId] || bootSequences['home'];
        let msgIndex = 0;
        
        function typeBootMessage() {
            if (msgIndex < bootMessages.length) {
                const p = document.createElement('div');
                p.textContent = bootMessages[msgIndex];
                bootText.appendChild(p);
                playTypingSound();
                
                // Update progress bar for 'about' page loader if it exists
                const progBar = document.getElementById('about-progress');
                if (progBar) {
                    progBar.style.width = ((msgIndex + 1) / bootMessages.length) * 100 + '%';
                }

                msgIndex++;
                setTimeout(typeBootMessage, Math.random() * 200 + 100);
            } else {
                setTimeout(() => {
                    loadingScreen.classList.add('fade-out');
                    setTimeout(() => loadingScreen.style.display = 'none', 800);
                    startTypewriter();
                }, 500);
            }
        }
        
        setTimeout(typeBootMessage, 200);
    } else {
        startTypewriter();
    }

    // Typewriter effect for Hero Section
    function startTypewriter() {
        const twText = document.getElementById('typewriter-text');
        if (!twText) return;
        
        const text = twText.dataset.typewriterText || "> Initializing System...";
        let i = 0;
        
        twText.innerHTML = '';
        
        function typeWriter() {
            if (i < text.length) {
                twText.innerHTML += text.charAt(i);
                playTypingSound();
                i++;
                setTimeout(typeWriter, 30 + Math.random() * 40);
            }
        }
        
        setTimeout(typeWriter, 500);
    }

    // Countdown Timer
    function updateCountdown() {
        const secsEl = document.getElementById('cd-secs');
        const minsEl = document.getElementById('cd-mins');
        const hoursEl = document.getElementById('cd-hours');
        const daysEl = document.getElementById('cd-days');
        
        if (!secsEl || !minsEl || !hoursEl || !daysEl) return;

        let s = parseInt(secsEl.innerText);
        let m = parseInt(minsEl.innerText);
        let h = parseInt(hoursEl.innerText);
        let d = parseInt(daysEl.innerText);

        s--;
        if (s < 0) {
            s = 59;
            m--;
            if (m < 0) {
                m = 59;
                h--;
                if (h < 0) {
                    h = 23;
                    d--;
                    if (d < 0) {
                        d = 0; h = 0; m = 0; s = 0; // timer reached zero
                    }
                    daysEl.innerText = d.toString().padStart(2, '0');
                }
                hoursEl.innerText = h.toString().padStart(2, '0');
            }
            minsEl.innerText = m.toString().padStart(2, '0');
        }
        secsEl.innerText = s.toString().padStart(2, '0');
    }

    setInterval(updateCountdown, 1000);

    // --- REAL-TIME NOTIFICATIONS POLLING ---
    const bellBtn = document.getElementById('bellBtn');
    const badge = document.getElementById('notificationBadge');
    const notifList = document.getElementById('notificationList');

    if (bellBtn && notifList) {
        let latestNotifTime = null;

        function fetchNotifications() {
            fetch('/api/notifications')
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data) {
                        const notifications = data.data;
                        notifList.innerHTML = '';
                        
                        if (notifications.length === 0) {
                            notifList.innerHTML = '<li class="dropdown-item text-secondary text-center">No new transmissions</li>';
                            return;
                        }

                        let maxTime = new Date(0);
                        
                        notifications.forEach(n => {
                            const li = document.createElement('li');
                            const time = new Date(n.created_at);
                            if (time > maxTime) maxTime = time;

                            const isEvent = n.target_type === 'event';
                            let roleColor = 'secondary';
                            let roleLabel = n.sender_role.toUpperCase();
                            
                            if (n.sender_role === 'root') {
                                roleColor = 'danger'; // High Priority (Red)
                                roleLabel = 'DIRECTOR';
                            } else if (n.sender_role === 'architect') {
                                roleColor = 'warning'; // Medium Priority (Yellow)
                                roleLabel = 'FACULTY';
                            } else if (n.sender_role === 'sudo') {
                                roleColor = 'info'; // Normal Priority (Cyan)
                                roleLabel = 'SUDO';
                            }

                            const badgeStr = isEvent ? '<span class="badge bg-secondary text-white me-2">[EVT]</span>' : '<span class="badge bg-secondary text-white me-2">[GLOBAL]</span>';
                            
                            li.innerHTML = `
                                <div class="dropdown-item text-wrap border-bottom border-secondary py-2" style="white-space: normal;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-secondary">${badgeStr} <span class="text-${roleColor}">[${roleLabel}] ${n.sender_name}</span></small>
                                        <small class="text-muted" style="font-size: 0.7rem;">${time.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</small>
                                    </div>
                                    <div class="text-white">${n.message}</div>
                                </div>
                            `;
                            notifList.appendChild(li);
                        });

                        latestNotifTime = maxTime.getTime();
                        const lastReadTime = localStorage.getItem('ck_last_read_notif') || 0;

                        if (latestNotifTime > lastReadTime) {
                            badge.classList.remove('d-none');
                            playHoverSound(); // Optional audio cue for new notification
                        }
                    }
                })
                .catch(err => console.error("Comms link failed: ", err));
        }

        // Poll every 10 seconds
        setInterval(fetchNotifications, 10000);
        // Initial fetch
        fetchNotifications();

        // Mark as read when clicking the bell
        bellBtn.addEventListener('click', () => {
            if (latestNotifTime) {
                localStorage.setItem('ck_last_read_notif', latestNotifTime);
                badge.classList.add('d-none');
            }
        });
    }

});
