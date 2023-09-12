
<div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here">
                        <ion-icon name="search-outline"></ion-icon>

                    </label>
                </div>
               <div class = "name_user" 
                    style = "   position: relative; 
                                color: var(--black2);
                                font-size: 1.1rem;"
                >
                    <?php
						$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
						$select_profile->execute([$admin_id]);
						$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                        echo  $fetch_profile['name'];                         

					?>
               </div>
                <div class="user">
                    <img src="../assets/imgs/customer01.jpg" alt="">
                </div>
                
            </div>