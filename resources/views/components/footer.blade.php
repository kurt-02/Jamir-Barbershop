<footer class="footer">
  <div class="scissors-line">
    <img src="{{ Vite::asset('resources/images/icons/Scissors.svg')}}" alt="">
    <hr class="dashed-line"/>
  </div>
  <div class="footer-info">
    <div class="footer-item">
      <img src="{{ Vite::asset('resources/images/icons/Clock.svg')}}" alt="">
      <div>
        <p>Mon–Fri: 8am–8pm</p>
        <p>Sat–Sun: 7am–9pm</p>
      </div>
    </div>
    <div class="footer-item">
    <img src="{{ Vite::asset('resources/images/icons/location.svg')}}" alt="">
      <p>West Molino 3 Bacoor, Cavite</p>
    </div>
    <div class="footer-item">
    <img src="{{ Vite::asset('resources/images/icons/Phone.svg')}}" alt="">
      <p>+63 905 275 3066</p>
    </div>
  </div>
</footer>


<style>
.footer {
  color: white;
  font-size: 14px;
}

.scissors-line {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 10px;
  gap: 10px;
}

.dashed-line {
  flex: 1;
  border: none;
  border-top: 3px dashed white;
}

.footer-info {
  display: flex;
  justify-content: space-around;
  align-items: flex-start;
  flex-wrap: wrap;
  text-align: center;
}

.footer-item {
  display: flex;
  flex-direction: row;
  align-items: center;
  margin: 10px;
}

.icon {
  font-size: 20px;
  margin-bottom: 5px;
}
</style>