 {{-- <!-- 💬 Botão Flutuante WhatsApp -->
 <a href="https://wa.me/55999181805?text=Olá!%20Quero%20falar%20sobre%20a%20plataforma%20de%20idiomas." target="_blank"
     rel="noopener"
     class="fixed bottom-5 right-5 z-50 flex items-center justify-center bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg transition-transform duration-300 hover:scale-110 w-14 h-14 animate-bounce"
     aria-label="Contato via WhatsApp">
     <!-- Ícone WhatsApp (SVG) -->
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="w-7 h-7">
         <path
             d="M16.04 3C9.39 3 4 8.38 4 15c0 2.52.74 4.87 2.02 6.87L4 29l7.35-1.95A12.03 12.03 0 0 0 16.04 27c6.64 0 12.04-5.38 12.04-12S22.68 3 16.04 3zm0 22c-1.82 0-3.6-.48-5.17-1.39l-.37-.21-4.35 1.16 1.16-4.23-.23-.39A9.97 9.97 0 0 1 6 15c0-5.53 4.5-10 10.04-10 5.54 0 10.04 4.47 10.04 10 0 5.53-4.5 10-10.04 10zm5.65-7.48c-.31-.15-1.84-.91-2.13-1.01-.28-.1-.49-.15-.7.15s-.8 1.01-.99 1.22-.36.23-.67.08c-.31-.15-1.31-.48-2.49-1.53a9.28 9.28 0 0 1-1.72-2.13c-.18-.31-.02-.48.13-.63.13-.13.31-.36.46-.54.15-.18.2-.31.31-.51.1-.2.05-.38-.03-.53-.08-.15-.7-1.67-.96-2.29-.25-.6-.51-.52-.7-.52h-.6c-.2 0-.53.08-.81.38s-1.06 1.04-1.06 2.54 1.09 2.94 1.25 3.14c.15.2 2.15 3.28 5.21 4.48.73.31 1.3.49 1.75.63.74.23 1.41.2 1.94.12.59-.09 1.84-.75 2.1-1.47.26-.73.26-1.34.18-1.47-.07-.13-.28-.2-.59-.36z" />
     </svg>
 </a> --}}

 <style>
     /* Efeito de pulos + leve rotação */
     @keyframes jumpRotate {
         0% {
             transform: translateY(0) rotate(0);
         }

         25% {
             transform: translateY(-8px) rotate(-3deg);
         }

         50% {
             transform: translateY(0) rotate(3deg);
         }

         75% {
             transform: translateY(-5px) rotate(-2deg);
         }

         100% {
             transform: translateY(0) rotate(0);
         }
     }

     /* Brilho pulsante */
     @keyframes glowPulse {
         0% {
             box-shadow: 0 0 10px rgba(37, 99, 235, 0.5);
         }

         50% {
             box-shadow: 0 0 25px rgba(37, 99, 235, 1);
         }

         100% {
             box-shadow: 0 0 10px rgba(37, 99, 235, 0.5);
         }
     }

     /* Balãozinho flutuando acima */
     @keyframes floatText {
         0% {
             transform: translateY(-3px);
         }

         50% {
             transform: translateY(3px);
         }

         100% {
             transform: translateY(-3px);
         }
     }
 </style>


 <!-- ⭐ Botão SUPER CHAMATIVO aulas ao vivo -->
 <div class="fixed bottom-36 right-5 z-50 flex flex-col items-end">

     <!-- Balão de texto -->
     <div
         class="mb-2 px-3 py-1 bg-blue-600 text-white text-sm font-semibold 
                rounded-full shadow-lg animate-[floatText_2.5s_ease-in-out_infinite]">
         📚 Aulas de Espanhol AO VIVO aos Sábados
     </div>

     <!-- Botão circular -->
     <a href="SEU_LINK_DO_ZOOM_AQUI" target="_blank" rel="noopener"
         class="flex items-center justify-center w-16 h-16 rounded-full text-white
              bg-blue-600 hover:bg-blue-700 transition-all
              animate-[jumpRotate_1.4s_ease-in-out_infinite]
              animate-[glowPulse_2s_infinite]">

         <!-- Ícone Vídeo -->
         <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-9 h-9">
             <path d="M17 10.5V7a2 2 0 0 0-2-2H5A2 2 0 0 0 3 7v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3.5l4 4v-11l-4 4z" />
         </svg>
     </a>
 </div>


 <!-- 💬 Botão Flutuante WhatsApp -->
 <a href="https://wa.me/55999181805?text=Olá!%20Quero%20falar%20sobre%20a%20plataforma%20de%20idiomas." target="_blank"
     rel="noopener"
     class="fixed bottom-5 right-5 z-50 flex items-center justify-center 
          bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg 
          transition-transform duration-300 hover:scale-110 w-14 h-14 
          animate-bounce">

     <!-- Ícone WhatsApp -->
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="w-7 h-7">
         <path
             d="M16.04 3C9.39 3 4 8.38 4 15c0 2.52.74 4.87 2.02 6.87L4 29l7.35-1.95A12.03 12.03 0 0 0 16.04 27c6.64 0 12.04-5.38 12.04-12S22.68 3 16.04 3zm0 22c-1.82 0-3.6-.48-5.17-1.39l-.37-.21-4.35 1.16 1.16-4.23-.23-.39A9.97 9.97 0 0 1 6 15c0-5.53 4.5-10 10.04-10 5.54 0 10.04 4.47 10.04 10 0 5.53-4.5 10-10.04 10zm5.65-7.48c-.31-.15-1.84-.91-2.13-1.01-.28-.1-.49-.15-.7.15s-.8 1.01-.99 1.22-.36.23-.67.08c-.31-.15-1.31-.48-2.49-1.53a9.28 9.28 0 0 1-1.72-2.13c-.18-.31-.02-.48.13-.63.13-.13.31-.36.46-.54.15-.18.2-.31.31-.51.1-.2.05-.38-.03-.53-.08-.15-.7-1.67-.96-2.29-.25-.6-.51-.52-.7-.52h-.6c-.2 0-.53.08-.81.38s-1.06 1.04-1.06 2.54 1.09 2.94 1.25 3.14c.15.2 2.15 3.28 5.21 4.48.73.31 1.3.49 1.75.63.74.23 1.41.2 1.94.12.59-.09 1.84-.75 2.1-1.47.26-.73.26-1.34.18-1.47-.07-.13-.28-.2-.59-.36z" />
     </svg>
 </a>
