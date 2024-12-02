var typedLeft = new Typed(".typing-left", {
    strings: ["Lost Something?", "Found Something?"],
    typeSpeed: 100,
    backSpeed: 60,
    loop: true,
    showCursor: true,
    cursorChar: '|',
    autoInsertCss: true
});

window.addEventListener('scroll', function() {
    const howItWorks = document.querySelector('.how-it-works-section');
    const scrollPosition = window.scrollY;
    
    if (scrollPosition > 50) {
        howItWorks.style.boxShadow = '0 -20px 30px rgba(0,0,0,0.1)';
    } else {
        howItWorks.style.boxShadow = 'none';
    }
});