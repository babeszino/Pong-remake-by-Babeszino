import labda from "./labda.js"
import uto from "./uto.js"

const labdacska = new labda(document.getElementById("labda"))
const jatekosUto = new uto(document.getElementById("jatekos-uto"))
const ellenfelUto = new uto(document.getElementById("ellenfel-uto"))
const jatekosPontja = document.getElementById("jatekos-pont")
const ellenfelPontja = document.getElementById("ellenfel-pont")
const kozepFelsoFal = new uto(document.getElementById("kozep-felso-fal"))
const kozepAlsoFal = new uto(document.getElementById("kozep-also-fal"))
const box = document.getElementById('box')
const boxFelirat = document.getElementById('box-felirat')
const playAgainButton = document.getElementById('play-again')

// var username = "legyeljo"

playAgainButton.addEventListener('click', () => window.location.reload())

const hatterZene = new Audio('./sounds/backgroundMusic.mp3')
hatterZene.loop = true
hatterZene.volume = 0.02
window.addEventListener('mousemove', function () {hatterZene.play()})

let legutobbiUpdate
function update(time) {
  if (legutobbiUpdate != null) {
    const elteltIdo = time - legutobbiUpdate
    labdacska.update(elteltIdo, [jatekosUto.rect(), ellenfelUto.rect(), kozepFelsoFal.rect(), kozepAlsoFal.rect()])
    ellenfelUto.update(elteltIdo, labdacska.y)

    if (korVege()) {
      korVegLekezeles()
    }
  }

  legutobbiUpdate = time
  window.requestAnimationFrame(update)
}

function nyertPontHang() {
  const nyertPont = new Audio('./sounds/pointwon.mp3')
  nyertPont.volume = 0.3
  nyertPont.play()
}

function vesztettPontHang() {
  const vesztettPont = new Audio('./sounds/pointlost.mp3')
  vesztettPont.volume = 0.15
  vesztettPont.play()
}

function korVege() {
  const rect = labdacska.rect()
  return rect.right >= window.innerWidth || rect.left <= 0
}

function korVegLekezeles() {
  const rect = labdacska.rect()
  if (rect.right >= window.innerWidth) {
    jatekosPontja.textContent = parseInt(jatekosPontja.textContent) + 1
    nyertPontHang()

    if (parseInt(jatekosPontja.textContent) === 5) {
      boxFelirat.textContent = "You won!"
      box.style.display = "block"

      fetch('update_stats.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${username}&result=win`
      });
    }
  }
  
  else {
    ellenfelPontja.textContent = parseInt(ellenfelPontja.textContent) + 1
    vesztettPontHang()

    if (parseInt(ellenfelPontja.textContent) === 5) {
      boxFelirat.textContent = "You lost. Try again!"
      box.style.display = "block"

      fetch('update_stats.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${username}&result=loss`
      });
      
    }
  }

  labdacska.alaphelyzet()
  ellenfelUto.alaphelyzet()
}

document.addEventListener("mousemove", e => {
  jatekosUto.pozicio = (e.y / window.innerHeight) * 100
})

window.requestAnimationFrame(update)