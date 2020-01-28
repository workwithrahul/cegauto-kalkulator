<?php

function cegauto_kalkulator_shortcode() {
  ob_start();
  ?>

  <section class="cegauto-kalkulator bootstrap-wrapper" id="cak_frontend">
    <div class="cegauto-kalkulator-inner container">
      <div class="loader"></div>

      <!-- INPUTS -->
      <!-- INPUT SECTION -->
      <div class="input-section">
        <div class="cegauto-inputs row no-gutters">
          <!-- BRUTTÓ VÉTELÁR -->
          <div class="cegauto-input col-sm-6 col-lg-3">
            <div class="cegauto-input-inner">
              <div class="cegauto-header">
				 
                <h6 class="cegauto-input-title">
                  Bruttó vételár
                </h6>
                <p class="cegauto-input-subtitle">
                  (Ft)
                </p>
				
				
              </div>

              <div class="cegauto-if-outer">

                <div class="cegauto-input-increment" id="vetelar">
                  <button class="inc-neg cak-btn-primary">-</button>
                  <span class="inc-val">
                    <span class="inc-val-value">6</span>
                    <span class="inc-val-unit">millió</span>
                  </span>
                  <button class="inc-pos cak-btn-primary">+</button>
                </div>

              </div>
            </div>
          </div>

          <!-- ÖNERŐ -->
          <div class="cegauto-input col-sm-6 col-lg-3">
            <div class="cegauto-input-inner">
              <div class="cegauto-header onero">
                <h6 class="cegauto-input-title">
                  Önerő
                </h6>
              </div>

              <div class="cegauto-if-outer">
                <div class="cegauto-input-range" id="berletidij">
                  <span class="berletidij-display-val">0%</span>
                  <div class="berletidij-slider-container">
                    <input type="range" name="berletidij-val" id="berletidij-val" class="berletidij-slider" min="0"
                           max="30" step="10" value="20">
                    <output for="berletidij-val"></output>
                  </div>
                  <span class="berletidij-display-val">30%</span>
                </div>
              </div>
            </div>
          </div>

          <!-- FUTAMIDŐ -->
          <div class="cegauto-input col-sm-6 col-lg-3">
            <div class="cegauto-input-inner">
              <div class="cegauto-header">
                <h6 class="cegauto-input-title">
                  Futamidő
                </h6>
                <p class="cegauto-input-subtitle">
                  (hónap)
                </p>
              </div>

              <div class="cegauto-if-outer">
                <div id="futamido-display-curr-val">
                </div>

                <div class="cegauto-input-options" id="futamido">
                  <div class="cegauto-options">
                    <label class="radio-btn">
                      <input type="radio" name="futamido-val" value="12" class="futamido-val">
                      <span class="checkmark"></span>
                    </label>

                    <label class="radio-btn">
                      <input type="radio" name="futamido-val" value="24" class="futamido-val">
                      <span class="checkmark"></span>
                    </label>

                    <label class="radio-btn">
                      <input type="radio" name="futamido-val" value="36" class="futamido-val" checked>
                      <span class="checkmark"></span>
                    </label>

                    <label class="radio-btn">
                      <input type="radio" name="futamido-val" value="48" class="futamido-val">
                      <span class="checkmark"></span>
                    </label>

                    <label class="radio-btn">
                      <input type="radio" name="futamido-val" value="60" class="futamido-val">
                      <span class="checkmark"></span>
                    </label>
                  </div>

                  <div id="futamido-display-values">
                    <span class="display-val">12</span>
                    <span class="display-val">24</span>
                    <span class="display-val">36</span>
                    <span class="display-val">48</span>
                    <span class="display-val">60</span>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- FUTÁSTELJESÍTMÉNY -->
          <div class="cegauto-input col-sm-6 col-lg-3">
            <div class="cegauto-input-inner">
              <div class="cegauto-header">
                <h6 class="cegauto-input-title">
                  Futásteljesítmény
                </h6>
                <p class="cegauto-input-subtitle">
                  (km/év)
                </p>
              </div>

              <div class="cegauto-if-outer">
                <div class="cegauto-input-dropdown" id="futasteljesitmeny">
                  <select name="futasteljesitmeny-val" id="futasteljesitmeny-val" class="selectric">
                    <option value="10000">10.000 km</option>
                    <option value="20000">20.000 km</option>
                    <option value="30000">30.000 km</option>
                    <option value="40000">40.000 km</option>
                    <option value="50000">50.000 km</option>
                    <option value="60000">60.000 km</option>
                  </select>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- INPUT SECTION -->

      <!-- INPUT SECTION -->
      <div class="input-section">
        <div class="row">
          <div class="col-md-7">
            <div class="cegauto-inputs row no-gutters">
              <!-- MOTOR TELJESÍTMÉNYE -->
              <div class="cegauto-input col-sm-12 mb">
                <div class="cegauto-input-inner">
                  <div class="cegauto-header">
                    <h6 class="cegauto-input-title">
                      Motor teljesítménye <span class="muted">(lóerő)</span>
                    </h6>
                  </div>

                  <div class="cegauto-if-outer">
                    <div class="cegauto-input-increment" id="loero">
                      <button class="inc-neg cak-btn-primary">-</button>
                      <span class="inc-val">
                    <span class="inc-val-value">150</span>
                  </span>
                      <button class="inc-pos cak-btn-primary">+</button>
                    </div>

                  </div>
                </div>
              </div>

              <!-- ÜZEMANYAG -->
              <div class="cegauto-input col-sm-12 mb">
                <div class="cegauto-input-inner">
                  <div class="cegauto-header">
                    <h6 class="cegauto-input-title">
                      Üzemanyag
                    </h6>
                  </div>

                  <div class="cegauto-if-outer">
                    <div class="cegauto-input-options-vertical" id="uzemanyag">
                      <div class="cegauto-options">
                        <label class="radio-btn">dízel
                          <input type="radio" name="uzemanyag-val" value="dizel" class="uzemanyag-val" checked>
                          <span class="checkmark"></span>
                        </label>

                        <label class="radio-btn">benzin
                          <input type="radio" name="uzemanyag-val" value="benzin" class="uzemanyag-val">
                          <span class="checkmark"></span>
                        </label>

                        <label class="radio-btn">hibrid
                          <input type="radio" name="uzemanyag-val" value="hibrid" class="uzemanyag-val">
                          <span class="checkmark"></span>
                        </label>

                        <label class="radio-btn">elektromos
                          <input type="radio" name="uzemanyag-val" value="elektromos" class="futamido-val">
                          <span class="checkmark"></span>
                        </label>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <!-- GÉPJÁRMŰ JELLEGE -->
              <div class="cegauto-input autok-wrapper col-sm-12 no-mb">
                <div class="cegauto-input-inner autok-inner">
                  <div class="cegauto-header">
                    <h6 class="cegauto-input-title autok-title">
                      Gépjármű jellege
                    </h6>
                  </div>

                  <div class="cegauto-if-outer autok-if-outer">

                    <div class="cegauto-input-autok" id="jarmujelleg">
                      <div class="cegauto-options">
                        <!-- SEDAN -->
                        <label class="radio-btn">
                      <span class="display-value">
                        <i class="cak-icon cak-icon-sedan"></i>
                        <p class="dv-title">sedan</p>
                      </span>

                          <input type="radio" name="jarmujelleg-val" value="sedan" class="jarmujelleg-val" checked>
                          <span class="checkmark"></span>
                        </label>

                        <!-- KOMBI -->
                        <label class="radio-btn">
                      <span class="display-value">
                        <i class="cak-icon cak-icon-kombi"></i>
                        <p class="dv-title">kombi</p>
                      </span>

                          <input type="radio" name="jarmujelleg-val" value="kombi" class="jarmujelleg-val">
                          <span class="checkmark"></span>
                        </label>

                        <!-- FERDEHATU -->
                        <label class="radio-btn">
                      <span class="display-value">
                        <i class="cak-icon cak-icon-kleinwagen"></i>
                        <p class="dv-title">ferdehátú</p>
                      </span>

                          <input type="radio" name="jarmujelleg-val" value="ferdehatu" class="jarmujelleg-val">
                          <span class="checkmark"></span>
                        </label>

                        <!-- SUV -->
                        <label class="radio-btn">
                      <span class="display-value">
                        <i class="cak-icon cak-icon-suv"></i>
                        <p class="dv-title">SUV</p>
                      </span>

                          <input type="radio" name="jarmujelleg-val" value="varositerepjaro" class="jarmujelleg-val">
                          <span class="checkmark"></span>
                        </label>

                        <!-- KISBUSZ -->
                        <label class="radio-btn">
                      <span class="display-value">
                        <i class="cak-icon cak-icon-minibusz"></i>
                        <p class="dv-title">egyterű</p>
                      </span>

                          <input type="radio" name="jarmujelleg-val" value="egyteru" class="jarmujelleg-val">
                          <span class="checkmark"></span>
                        </label>

                        <!-- COUPE -->
                        <label class="radio-btn">
                      <span class="display-value">
                        <i class="cak-icon cak-icon-coupe"></i>
                        <p class="dv-title">sportautó</p>
                      </span>

                          <input type="radio" name="jarmujelleg-val" value="sportauto" class="jarmujelleg-val">
                          <span class="checkmark"></span>
                        </label>

                        <!-- TEREPJARO -->
                        <label class="radio-btn">
                      <span class="display-value">
                        <i class="cak-icon cak-icon-suv"></i>
                        <p class="dv-title">terepjáró</p>
                      </span>

                          <input type="radio" name="jarmujelleg-val" value="terepjaro" class="jarmujelleg-val">
                          <span class="checkmark"></span>
                        </label>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <!-- PRICE -->
            <div class="cegauto-price">
              <div class="price-packages">
                <div class="price-package light">
                  <div class="price-package-inner">
                    <h6 class="package-name">Nettó havi díj:</h6>
                    <p class="package-price" id="havidij-price"></p>
                  </div>
                </div>

                <div class="price-package primary">
                  <div class="price-package-inner">
                    <h6 class="package-name">Nettó induló díj:</h6>
                    <p class="package-price" id="onero-price"></p>
                  </div>
                </div>
                <div class="price-package dark">
                  <div>
                    <div class="price-package-inner">
                      <a href="#kalkulator" class="cak-cta">Ajánlatkérés</a>
                      <p class="cta-text">Ha felkeltettük érdeklődését, kérjen személyre szabott ajánlatot még ma!</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- PRICE -->
          </div>
          <p class="price-note">*Az összeg amit lát, a finanszírozást és az adókat, biztosításokat tartalmazza, a
            szolgáltatásokat nem. A bérleti díj ÁFA tartalma minimum 50%-ban, de útnyilvántartás vezetésével akár 100%-ban is visszaigényelhető.
          </p>
        </div>
      </div>
      <!-- INPUT SECTION -->
      <!-- INPUTS -->

      <!-- FORM -->
      <div class="cegauto-form-wrapper" id="kalkulator">
        <h2 class="form-title">Kedve támadt továbblépni, pontos ajánlatot kérni, meghallgatni kollégáink tippjeit? Adja
          meg elérhetőségét, írjon nekünk!</h2>

        <form class="cegauto-form">

          <div class="form-body">
            <div class="form-items row">
              <div class="col-md-6 form-col">
                <input type="text" class="form-item" id="nev" placeholder="Név *" required>
              </div>
              <div class="col-md-6 form-col">
                <input type="email" class="form-item" id="email" placeholder="Email cím *" required>
              </div>
              <div class="col-md-6 form-col">
                <input type="text" class="form-item" id="telefonszam" placeholder="Telefonszám *" required>
              </div>
              <div class="col-md-6 form-col">
                <input type="text" class="form-item" id="cegnevadoszam" placeholder="Cégnév vagy adószám">
              </div>
              <div class="col-md-6 form-col">
                <select required id="min1lezart_ev">
                  <option value="">Rendelkezik-e cége minimum 1 lezárt évvel?</option>
                  <option value="igen">Igen, rendelkezik</option>
                  <option value="nem">Nem rendelkezik</option>
                </select>
              </div>
              <div class="col-md-6 form-col">
                <select required id="ker_visszahivast">
                  <option value="">Kér visszahívást?</option>
                  <option value="igen">Igen, kérek.</option>
                  <option value="nem">Nem kérek.</option>
                </select>
              </div>
              <div class="col-md-12 form-col" id="gyartmany">
                <input type="text" class="form-item" name="gyartmany-val" id="gyartmany-val"
                       placeholder="Milyen márkák érdeklik? Egyéb üzenet...">
              </div>
            </div>
          </div>

          <div class="form-footer">
            <div class="form-footer-items row">

              <div class="form-checkboxes col-md-6">
                <div class="form-checkboxes-inner">
                  <label for="keszletesauto" class="checkbox-btn">
                    <input type="checkbox" id="keszletesauto">
                    <span class="checkmark"></span>
                    Készletes autó érdekel.
                  </label>
                  <label for="hirlevel" class="checkbox-btn">
                    <input type="checkbox" id="hirlevel">
                    <span class="checkmark"></span>
                    Feliratkozom a hírlevélre.
                  </label>
                  <label for="szabalyzat" class="checkbox-btn">
                    <input type="checkbox" id="szabalyzat" required class="required-checkbox">
                    <span class="checkmark"></span>
                    Elfogadom az adatkezelési szabályzatot.
                    <span class="must-fill-star">*</span>
                  </label>
                </div>

                <div class="g-recaptcha" data-sitekey="6LdiE7sUAAAAANSYtFgy0MMIVj61qwvAa4vXh_TB"></div>
              </div>

              <div class="form-submit col-md-6">
                <input type="submit" class="cak-btn-secondary submit-button" id="ajanlatkeres" value="Ajánlatot kérek!"
                       disabled>
              </div>

            </div>
            <p class="error">
              Ezzel az email-címmel már feliratkoztál a listára!
            </p>
          </div>

        </form>
      </div>
      <!-- FORM -->

    </div>
  </section>

  <?php
  return ob_get_clean();
}

add_shortcode( 'cegautokalkulator', 'cegauto_kalkulator_shortcode' );
