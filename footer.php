  </main>
</div>

<script>

document.addEventListener('click', function(e){
  const sb = document.querySelector('.sidebar');
  const hb = document.querySelector('.hamburger');
  if(sb && hb && !sb.contains(e.target) && !hb.contains(e.target)){
    sb.classList.remove('open');
  }
});
</script>
</body>
</html>
