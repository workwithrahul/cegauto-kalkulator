class Maradvanyertek {
  constructor() {
    //get matrices from WP API
    this.adminEgyEvMatrix = window.AdminMaradvanyertek.egyEvMatrix;
    this.adminKetEvMatrix = window.AdminMaradvanyertek.ketEvMatrix;
    this.adminHaromEvMatrix = window.AdminMaradvanyertek.haromEvMatrix;
    this.adminNegyEvMatrix = window.AdminMaradvanyertek.negyEvMatrix;
    this.adminOtEvMatrix = window.AdminMaradvanyertek.otEvMatrix;

    //parse matrices
    this.adminEgyEvMatrix.forEach((x,) => {
      x.forEach((y, index, array) => {
        array[index] = parseInt(y);
      });
    });
    this.adminKetEvMatrix.forEach((x,) => {
      x.forEach((y, index, array) => {
        array[index] = parseInt(y);
      });
    });
    this.adminHaromEvMatrix.forEach((x,) => {
      x.forEach((y, index, array) => {
        array[index] = parseInt(y);
      });
    });
    this.adminNegyEvMatrix.forEach((x,) => {
      x.forEach((y, index, array) => {
        array[index] = parseInt(y);
      });
    });
    this.adminOtEvMatrix.forEach((x,) => {
      x.forEach((y, index, array) => {
        array[index] = parseInt(y);
      });
    });

    // helper matrices
    this.evesfutasok = [10000, 20000, 30000, 40000, 50000, 60000];
    this.bruttoRanges = [
      {
        min: 0,
        max: 5499999,
      },
      {
        min: 5500000,
        max: 7999999,
      },
      {
        min: 8000000,
        max: 11499000,
      },
      {
        min: 11500000,
        max: 17999999,
      },
      {
        min: 18000000,
        max: 28999999,
      },
      {
        min: 29000000,
        max: 35999999,
      },
      {
        min: 36000000,
        max: Number.MAX_SAFE_INTEGER,
      },
    ];
  }

  /**
   * Calculates 'MÉ százalék' based on 4 parameters.
   *
   * @param futamido
   * @param futasTeljesitmeny
   * @param bruttoVetelar
   * @param uzemanyag
   *
   * @returns Integer
   */
  calculateMaradvanyErtekSzazalek(
    futamido,
    futasTeljesitmeny,
    bruttoVetelar,
    uzemanyag,
  ) {
    let maradvanyErtekSzazalek = 0;

    /**
     * Steps to find value.
     * 1. Find futasTeljesitmeny index in array this.evesfutasok -> x
     * 2. Find bruttoVetelar index in array this.bruttoRanges -> y
     * 3. Decide which matrice to use (futamido)
     * 4. Get the matrice's (x,y)-th element
     * 5. If 'uzemanyag' equals 'dízel' then subtract 1 from the result
     */

    // 1.
    const x = this.evesfutasok.indexOf(futasTeljesitmeny);

    // 2.
    let y = 0;
    this.bruttoRanges.forEach((item, index) => {
      const min = parseInt(item.min);
      const max = parseInt(item.max);

      if ( bruttoVetelar >= min && bruttoVetelar <= max ) {
        y = index;
      }
    });

    // 3.
    switch (futamido) {
      case 12:
        // 4.
        maradvanyErtekSzazalek = this.adminEgyEvMatrix[x][y];
        break;
      case 24:
        // 4.
        maradvanyErtekSzazalek = this.adminKetEvMatrix[x][y];
        break;
      case 36:
        // 4.
        maradvanyErtekSzazalek = this.adminHaromEvMatrix[x][y];
        break;
      case 48:
        // 4.
        maradvanyErtekSzazalek = this.adminNegyEvMatrix[x][y];
        break;
      case 60:
        // 4.
        maradvanyErtekSzazalek = this.adminOtEvMatrix[x][y];
        break;
    }

    // 5.
    if ( uzemanyag === 'dizel' ) {
      return maradvanyErtekSzazalek - 1;
    } else {
      return maradvanyErtekSzazalek;
    }
  }
}

export default Maradvanyertek;
