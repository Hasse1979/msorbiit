<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en fi">
    <title>M/S Orbiit Galleria</title>

<!-- Carousel wrapper -->
<div
  id="carouselBasicExample"
  data-mdb-carousel-init class="carousel slide carousel-fade"
  data-mdb-ride="carousel"
>
  <!-- Indicators -->
  <div class="carousel-indicators">
    <button data-mdb-button-init
      type="button"
      data-mdb-target="#carouselBasicExample"
      data-mdb-slide-to="0"
      class="active"
      aria-current="true"
      aria-label="Slide 1"
    ></button>
    <button data-mdb-button-init
      type="button"
      data-mdb-target="#carouselBasicExample"
      data-mdb-slide-to="1"
      aria-label="Slide 2"
    ></button>
    <button data-mdb-button-init
      type="button"
      data-mdb-target="#carouselBasicExample"
      data-mdb-slide-to="2"
      aria-label="Slide 3"
    ></button>
  </div>

  <!-- Inner -->
  <div class="carousel-inner">
    <!-- Single item -->
    <div class="carousel-item active">
      <img
        src="'msorbiitkuvat/msorbiit2.jpg'"
        class="d-block w-100"
        alt="laivan kuva"
      />
      <div class="carousel-caption d-none d-md-block">
        <h5>First slide label</h5>
        <p>
          Nulla vitae elit libero, a pharetra augue mollis interdum.
        </p>
      </div>
    </div>

    <!-- Single item -->
    <div class="carousel-item">
      <img
        src="msorbiitkuvat/msorbiit.jpg"
        class="d-block w-100"
        alt="laivan logo"
      />
      <div class="carousel-caption d-none d-md-block">
        <h5>Second slide label</h5>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        </p>
      </div>
    </div>

    <!-- Single item -->
    <div class="carousel-item">
      <img
        src="https://mdbcdn.b-cdn.net/img/Photos/Slides/img%20(23).webp"
        class="d-block w-100"
        alt="Cliff Above a Stormy Sea"
      />
      <div class="carousel-caption d-none d-md-block">
        <h5>Third slide label</h5>
        <p>
          Praesent commodo cursus magna, vel scelerisque nisl consectetur.
        </p>
      </div>
    </div>
  </div>
  <!-- Inner -->

  <!-- Controls -->
  <button data-mdb-button-init
    class="carousel-control-prev"
    type="button"
    data-mdb-target="#carouselBasicExample"
    data-mdb-slide="prev"
  >
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button data-mdb-button-init
    class="carousel-control-next"
    type="button"
    data-mdb-target="#carouselBasicExample"
    data-mdb-slide="next"
  >
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
</body>
</html>
<?php include 'footer.php'; ?>