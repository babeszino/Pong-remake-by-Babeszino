const ALAPGYORSASAG = 0.025
const GYORSASAGSZORZO = 0.000007

export default class Labda {
  constructor(labdaElem) {
    this.labdaElem = labdaElem
    this.alaphelyzet()
  }

  get x() {
    return parseFloat(getComputedStyle(this.labdaElem).getPropertyValue("--x"))
  }

  set x(ertek) {
    this.labdaElem.style.setProperty("--x", ertek)
  }

  get y() {
    return parseFloat(getComputedStyle(this.labdaElem).getPropertyValue("--y"))
  }

  set y(ertek) {
    this.labdaElem.style.setProperty("--y", ertek)
  }

  rect() {
    return this.labdaElem.getBoundingClientRect()
  }

  alaphelyzet() {
    this.x = 50
    this.y = 50
    this.irany = { x: 0 }
    while (
      Math.abs(this.irany.x) <= 0.2 ||
      Math.abs(this.irany.x) >= 0.9
    ) {
      const randomIrany = randomKetszamKozt(0, 2 * Math.PI)
      this.irany = { x: Math.cos(randomIrany), y: Math.sin(randomIrany) }
    }
    this.sebesseg = ALAPGYORSASAG
  }

  update(elteltIdo, utoRect) {
    this.x += this.irany.x * this.sebesseg * elteltIdo
    this.y += this.irany.y * this.sebesseg * elteltIdo
    this.sebesseg += GYORSASAGSZORZO * elteltIdo
    const rect = this.rect()

    function utesHangLejatszas() {
      const utesHang = new Audio('./sounds/hit.mp3')
      utesHang.volume = 0.2
      utesHang.play()
    }

    if (rect.bottom >= window.innerHeight || rect.top <= 0) {
      this.irany.y *= -1
      utesHangLejatszas()
    }

    if (utoRect.some(r => labdaUtkozes(r, rect))) {
      this.irany.x *= -1
      utesHangLejatszas()
    }
  }
}

function randomKetszamKozt(min, max) {
  return Math.random() * (max - min) + min
}

function labdaUtkozes(rect1, rect2) {
  return (
    rect1.left <= rect2.right &&
    rect1.right >= rect2.left &&
    rect1.top <= rect2.bottom &&
    rect1.bottom >= rect2.top
  )
}