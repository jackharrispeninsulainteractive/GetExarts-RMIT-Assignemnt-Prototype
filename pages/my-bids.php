<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 12/06/2022
 */

if(!$authed){
    echo "<div id=redirect>login</div>";
}else{

    ?>

    <main>
        <section>
            <h1>My Bids</h1>

            <table>
                <thead>
                <th>Task Name</th>
                <th>Bid Validated</th>
                <th>Author</th>
                <th>Outcome</th>
                </thead>
                <tbody>
                <?php
                $user_id = $user->getId();
                $bids = Database::query("SELECT * FROM bids WHERE user_id='$user_id'")->fetch_all(MYSQLI_ASSOC);
                foreach ($bids as $bid){
                    $task_id = $bid["task_id"];
                    $task = Database::query("SELECT * FROM tasks WHERE id='$task_id'")->fetch_all(MYSQLI_ASSOC);

                    echo "<tr>";
                    echo "<td>".$task[0]["name"]."</td>";
                    if(!$bid["validated"]){
                        echo "<td><button onclick='Application.instance.openValidationWindow(".$bid["bid_id"].")'>Validate bid</button></td>";
                    }else{
                        echo "<td style='color=green'>Bid Validated!</td>";
                    }
                    echo "<td>".$task[0]["author"]."</td>";
                    echo "<td>Feature not yet implemented</td>";


                    echo "</tr>";

                }
                ?>
                </tbody>
            </table>

        </section>
    </main>


    <div id="new-task-window">
        <div>
            <h2>Bid Validation</h2>
            <form action="javascript:Application.instance.validateBid()">
                <p id="new-task-window-error" style=" color: red; text-align: center"></p>
                <label style="width: 100%!important;">
                    Re-Enter your bid amount with no dollar sign
                    <input type="text" placeholder="Example: 400" required id="bid-validation-input">
                </label>
                <input type="number" disabled id="bid-validation-id" style="display: none">
                <button style="background-color: green; width: 100%">Save</button>
                <button style="background-color: red;width: 100%" onclick="Application.instance.cancelNewTaskWindowButton()">Cancel</button>
            </form>
        </div>
    </div>

    <style>
        table{
            color: white;
            width: 100%;
            border-collapse: collapse;
        }
        table thead{
            background-color: #383636;
            color: white;
            border: 1px solid #595959;

        }
        table thead th{
            font-weight: normal;
            text-align: left;
            padding: 16px;
        }
        table tbody td{
            padding: 8px;
            border: 1px solid #595959;
            overflow: scroll;
        }
        button{
            background-color: #ff6200;
            height: 48px;
            padding:8px;
            width: 100%;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
            outline: 0 none;
            border-color: #ff6200;
            border-radius: 8px;
            color: white;
        }
        button:hover{
            cursor: pointer;
        }
    </style>

    <?php
}