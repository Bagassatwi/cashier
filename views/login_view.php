<!DOCTYPE html>
<html lang="en">
<?php include './head.php' ?>

<body class="bg-gray-50">
  <div class="flex items-center justify-center min-h-screen">
    <section class="rounded-xl size-fit p-4 bg-white shadow-2xl">
      <h1 class="text-[2em] font-bold">Admin Login</h1>
      <hr class="font-bold h-[2px] border-0 bg-[#333] mb-4">
      <form action="login_action.php" method="post" class="flex flex-col gap-4 justify-around w-[25dvw] h-auto">
        <div class="flex flex-col">
          <label class="text-[1.5em]" for="username">Username</label>
          <input class="border border-[#444] rounded-lg p-1" type="text" name="username" id="username" />
        </div>
        <div class="flex flex-col">
          <label class="text-[1.5em]" for="password">Password</label>
          <input class="border border-[#444] rounded-lg p-1" type="password" name="password" id="password" />
        </div>
        <div class="flex flex-row justify-between">
          <button type="reset" class="hover:bg-gray-700 w-[45%] px-4 py-2 font-bold text-black hover:text-white border-2 border-black rounded">Clear</button>
          <button type="submit" class="hover:bg-blue-700 w-[45%] px-4 py-2 font-bold text-white bg-blue-500 border-2 border-black rounded">Log In</button>
        </div>
      </form>
    </section>
  </div>
</body>

</html>