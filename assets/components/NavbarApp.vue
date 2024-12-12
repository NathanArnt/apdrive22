<template>
  <div class="navBar">
    <div class="navBar-container">
      <h1>DantecDrive</h1>
      <div class="links">
        <a href="/">Produits</a>
        <a href="">Promotions</a>
        <a href="">Drive</a>
      </div>
      <button>
        <a href="/login"><i class="fa-solid fa-user"></i></a>
      </button>
    </div>
  </div>
</template>
  
<script>
  export default {
    name: 'NavbarApp',
  };
  </script>
  
<style scoped>
  .navBar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 850px;
    margin: auto;
    position: relative;  /* Position relative pour bien positionner les éléments sous le total */
    padding-bottom: 30px;
  }
  .navBar-container h1{
    color: #ee0653;
  }
  .navBar-container button a {
    color: #ee0653;
    font-size: 1.5rem;
  }
  .navBar-container button{
    border: none;
    background: none;
    transition: 0.3s ease;
  }
  .navBar-container button:hover{
    transform: scale(1.3);
  }
  .navBar-container .links {
    display: flex;
    color: black;
  }
  .navBar-container .links a {
    position: relative;
    margin: 20px ; 
    cursor: pointer;
    color: black;
    font-size: 1.6rem;
    text-decoration: none;
  }
  .navBar-container .links a::before {
    content: "";
    position: absolute;
    background-color: #ee0653;
    width: 0%;
    height: 2px;
    left: 0;
    bottom: -10px;
    transition: 0.3s ease;
    border-radius: 15%;
  }
  .navBar-container .links a:hover::before {
    width: 100%;
  }
  .navBar-container h1 {
    color: #ee0653;
  }
  .navBar-container .links {
    display: flex;
    color: black;
  }
</style>
  