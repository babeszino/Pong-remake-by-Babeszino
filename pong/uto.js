const GYORSASAG = 0.02

export default class Uto {
  constructor(utoPozicio) {
    this.utoPozicio = utoPozicio
    this.alaphelyzet()
  }

  get pozicio() {
    return parseFloat(
      getComputedStyle(this.utoPozicio).getPropertyValue("--pozicio")
    )
  }

  set pozicio(ertek) {
    this.utoPozicio.style.setProperty("--pozicio", ertek)
  }

  rect() {
    return this.utoPozicio.getBoundingClientRect()
  }

  alaphelyzet() {
    this.pozicio = 50
  }

  update(elteltIdo, labdaPozi) {
    this.pozicio += GYORSASAG * elteltIdo * (labdaPozi - this.pozicio)
  }
}