/* -----------------------------------------------
/* How to use? : Check the GitHub README
/* ----------------------------------------------- */

/* To load a config file (particles.json) you need to host this demo (MAMP/WAMP/local)... */
/*
particlesJS.load('particles-js', 'particles.json', function() {
  console.log('particles.js loaded - callback');
});
*/

/* Otherwise just put the config content (json): */

particlesJS('particles-js',
  
{
  "particles": {
    "number": {
      "value": 43,
      "density": {
        "enable": true,
        "value_area": 800
      }
    },
    "color": {
      "value": "#ffffff"
    },
    "shape": {
      "type": "circle",
      "stroke": {
        "width": 0,
        "color": "#000000"
      },
      "polygon": {
        "nb_sides": 3
      },
      "image": {
        "src": "data:image/png;base64,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",
        "width": 100,
        "height": 100
      }
    },
    "opacity": {
      "value": 1,
      "random": false,
      "anim": {
        "enable": false,
        "speed": 1,
        "opacity_min": 0.1,
        "sync": false
      }
    },
    "size": {
      "value": 2,
      "random": true,
      "anim": {
        "enable": false,
        "speed": 40,
        "size_min": 0.1,
        "sync": false
      }
    },
    "line_linked": {
      "enable": false,
      "distance": 2000,
      "color": "#ffffff",
      "opacity": 0,
      "width": 0
    },
    "move": {
      "enable": true,
      "speed": 11.22388442605866,
      "direction": "none",
      "random": false,
      "straight": false,
      "out_mode": "out",
      "bounce": false,
      "attract": {
        "enable": false,
        "rotateX": 600,
        "rotateY": 1200
      }
    }
  },
  "interactivity": {
    "detect_on": "canvas",
    "events": {
      "onhover": {
        "enable": false,
        "mode": "grab"
      },
      "onclick": {
        "enable": false,
        "mode": "push"
      },
      "resize": true
    },
    "modes": {
      "grab": {
        "distance": 400,
        "line_linked": {
          "opacity": 1
        }
      },
      "bubble": {
        "distance": 400,
        "size": 40,
        "duration": 2,
        "opacity": 8,
        "speed": 3
      },
      "repulse": {
        "distance": 200,
        "duration": 0.4
      },
      "push": {
        "particles_nb": 4
      },
      "remove": {
        "particles_nb": 2
      }
    }
  },
  "retina_detect": true
});