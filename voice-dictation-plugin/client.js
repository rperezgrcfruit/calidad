window.__ModuleLoader__.load({
  id: '@local/voice-dictation',
  factory(require) {
    const React = require('react');
    const h = React.createElement;
    const { useState, useEffect, useRef } = React;

    function VoiceDictationButton() {
      const [status, setStatus] = useState('idle'); // 'idle' | 'requesting' | 'listening' | 'error'
      const [errorMessage, setErrorMessage] = useState('');
      const recognitionRef = useRef(null);
      const mediaStreamRef = useRef(null);
      const transcriptRef = useRef('');

      useEffect(() => {
        return () => {
          stopAll();
        };
      }, []);

      const stopAll = () => {
        if (recognitionRef.current) {
          try { recognitionRef.current.stop(); } catch (e) {}
          recognitionRef.current = null;
        }
        if (mediaStreamRef.current) {
          try {
            mediaStreamRef.current.getTracks().forEach(track => track.stop());
          } catch (e) {}
          mediaStreamRef.current = null;
        }
      };

      const insertTextToComposer = (text) => {
        if (!text || !text.trim()) return;
        const cleanText = text.trim();
        
        const textarea = document.querySelector('textarea[placeholder], textarea, .ProseMirror, [contenteditable="true"]');
        if (!textarea) return;

        if (textarea.tagName === 'TEXTAREA' || textarea.value !== undefined) {
          const startPos = textarea.selectionStart ?? textarea.value.length;
          const endPos = textarea.selectionEnd ?? textarea.value.length;
          const value = textarea.value;
          const prefix = (startPos > 0 && !/\s$/.test(value.slice(0, startPos))) ? ' ' : '';
          
          textarea.value = value.slice(0, startPos) + prefix + cleanText + value.slice(endPos);
          const newCursorPos = startPos + prefix.length + cleanText.length;
          if (textarea.setSelectionRange) {
            textarea.setSelectionRange(newCursorPos, newCursorPos);
          }
          textarea.dispatchEvent(new Event('input', { bubbles: true }));
          textarea.dispatchEvent(new Event('change', { bubbles: true }));
          textarea.focus();
        } else if (textarea.isContentEditable) {
          textarea.focus();
          document.execCommand('insertText', false, ' ' + cleanText);
        }
      };

      const handleToggle = async () => {
        if (status === 'listening' || status === 'requesting') {
          if (transcriptRef.current) {
            insertTextToComposer(transcriptRef.current);
          }
          stopAll();
          setStatus('idle');
          return;
        }

        setStatus('requesting');
        setErrorMessage('');
        transcriptRef.current = '';

        try {
          // Solicitar acceso al micrófono explícitamente en Electron/Windows
          const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
          mediaStreamRef.current = stream;

          const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

          if (!SpeechRecognition) {
            setErrorMessage('Reconocimiento de voz no soportado en este entorno.');
            setStatus('error');
            setTimeout(() => setStatus('idle'), 4000);
            return;
          }

          const rec = new SpeechRecognition();
          recognitionRef.current = rec;

          rec.continuous = true;
          rec.interimResults = true;
          rec.lang = navigator.language || 'es-ES';

          rec.onstart = () => {
            setStatus('listening');
          };

          rec.onresult = (event) => {
            let currentTranscript = '';
            for (let i = 0; i < event.results.length; i++) {
              currentTranscript += event.results[i][0].transcript;
            }
            transcriptRef.current = currentTranscript;
          };

          rec.onerror = (event) => {
            console.warn('[Dictado] Error:', event.error);
            if (event.error === 'not-allowed') {
              setErrorMessage('Acceso al micrófono denegado.');
            } else if (event.error === 'no-speech') {
              return;
            } else {
              setErrorMessage('Error: ' + event.error);
            }
            stopAll();
            setStatus('error');
            setTimeout(() => setStatus('idle'), 4000);
          };

          rec.onend = () => {
            if (recognitionRef.current && status === 'listening') {
              try { rec.start(); } catch (e) {}
            }
          };

          rec.start();
        } catch (err) {
          console.error('[Dictado] Error:', err);
          setErrorMessage('Error de micrófono: ' + (err.message || err.name));
          stopAll();
          setStatus('error');
          setTimeout(() => setStatus('idle'), 4000);
        }
      };

      const isListening = status === 'listening';
      const isRequesting = status === 'requesting';
      const isError = status === 'error';

      return h('div', {
        style: {
          display: 'inline-flex',
          alignItems: 'center',
          position: 'relative'
        }
      },
        h('button', {
          className: 'voice-dictation-btn',
          onClick: handleToggle,
          type: 'button',
          title: isListening ? 'Detener dictado y transcribir' : 'Dictar por voz',
          style: {
            display: 'inline-flex',
            alignItems: 'center',
            justifyContent: 'center',
            width: '32px',
            height: '32px',
            borderRadius: '8px',
            border: isListening ? '1px solid #3b82f6' : isError ? '1px solid #ef4444' : '1px solid rgba(255, 255, 255, 0.2)',
            backgroundColor: isListening ? 'rgba(59, 130, 246, 0.25)' : isError ? 'rgba(239, 68, 68, 0.2)' : 'rgba(255, 255, 255, 0.05)',
            color: isListening ? '#60a5fa' : isError ? '#f87171' : 'inherit',
            cursor: 'pointer',
            padding: 0,
            marginRight: '6px',
            transition: 'all 0.2s ease',
            outline: 'none',
            boxShadow: isListening ? '0 0 10px rgba(59, 130, 246, 0.6)' : 'none'
          }
        },
          isRequesting
            ? h('svg', { viewBox: '0 0 24 24', width: 16, height: 16, fill: 'none', stroke: 'currentColor', strokeWidth: 2, style: { animation: 'spin 1s linear infinite' } },
                h('circle', { cx: 12, cy: 12, r: 10, strokeDasharray: '32', strokeDashoffset: '10' })
              )
            : h('svg', { viewBox: '0 0 24 24', width: 16, height: 16, fill: 'none', stroke: 'currentColor', strokeWidth: 2 },
                h('path', { d: 'M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z' }),
                h('path', { d: 'M19 10v2a7 7 0 0 1-14 0v-2' }),
                h('line', { x1: 12, y1: 19, x2: 12, y2: 22 })
              )
        ),
        isError && h('div', {
          style: {
            position: 'absolute',
            bottom: '100%',
            right: 0,
            marginBottom: '8px',
            padding: '6px 10px',
            backgroundColor: '#1f2937',
            color: '#f87171',
            border: '1px solid #ef4444',
            borderRadius: '6px',
            fontSize: '11px',
            whiteSpace: 'nowrap',
            boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.4)',
            zIndex: 100
          }
        }, errorMessage)
      );
    }

    return {
      inject: ['slots'],
      apply(ctx) {
        ctx.slots.inject('conversation.input.right', () => {
          ctx.slots.register({
            name: 'conversation.input.right',
            id: 'voice-dictation',
            order: 1000,
          }, VoiceDictationButton);
        });
      },
    };
  },
});