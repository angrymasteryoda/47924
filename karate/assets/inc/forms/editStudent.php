<form class="input editStudentForm" studentId="<?php echo ( ( empty($data[0]['student_id']) ) ? ('') : ($data[0]['student_id']) ) ?>">
    <p id="errors">

    </p>
    <label>Name:
        <input name="name" type="text" placeholder="Name" value="<?php echo ( ( empty($data[0]['name']) ) ? ('') : ($data[0]['name']) ) ?>" data-type="name"/>
    </label>
    <label>Email:
        <input name="email" type="text" placeholder="Email" value="<?php echo ( ( empty($data[0]['email']) ) ? ('') : ($data[0]['email']) ) ?>" data-type="email"/>
    </label>
    <label class="floatleft phoneInput">Phone Number:
        <input class="" name="phone" type="text" placeholder="Phone Number" value="<?php echo ( ( empty($data[0]['phone']) ) ? ('') : ($data[0]['phone']) ) ?>" data-type="usphone" />

    </label>
    <label class="floatleft extensionInput">Extension:
        <input class="" name="extension" type="text" placeholder="Extension" value="<?php echo ( ( empty($data[0]['extension']) ) ? ('') : ($data[0]['extension']) ) ?>" data-type="extension" />
    </label>
    <div class="clear"></div>
    <label>Age:
        <input name="age" type="text" placeholder="Age" value="<?php echo ( ( empty($data[0]['age']) ) ? ('') : ($data[0]['age']) ) ?>" data-type="numbers"/>
    </label>
    <label>Who are the lessons for<br/>
        Child: <input name="whoFor" type="radio" value="Child" data-type="whoFor" data-pass="true" <?php echo ( ($data[0]['is_child']) ? ('checked') : ('') )?>/>
        Self:<input name="whoFor" type="radio" value="Self" data-type="whoFor" data-pass="false" <?php echo ( (!$data[0]['is_child']) ? ('checked') : ('') )?>/><br/>
    </label>
    <div class="<?php echo ( ($data[0]['is_child']) ? ('') : ('none') )?> whoFor margin10_top">
        <label>Parent Name
            <input type="text" placeholder="Parent Name" name="pName" value="<?php echo ( ( empty($data[0]['parent_name']) ) ? ('') : ($data[0]['parent_name']) ) ?>" data-type="pName" data-parent="whoFor"/>
        </label>
        <label>Parent Cell Phone
            <input type="text" placeholder="Parent Cell Phone" name="pCell" value="<?php echo ( ( empty($data[0]['parent_cell']) ) ? ('') : ($data[0]['parent_cell']) ) ?>" data-type="pCell" data-parent="whoFor"/>
        </label>
    </div>
    <label>Past Experience<br/>
        Yes: <input name="experience" type="radio" value="Yes" data-type="experience" data-pass="true" <?php echo ( ($data[0]['past_experience']) ? ('checked') : ('') )?>/>
        No:<input name="experience" type="radio" value="No" data-type="experience" data-pass="false" <?php echo ( (!$data[0]['past_experience']) ? ('checked') : ('') )?>/><br/>
    </label>
    <div class="<?php echo ( ($data[0]['past_experience']) ? ('') : ('none') )?> experience margin10_top">
        <label>Current Belt
            <input type="text" placeholder="Current Belt" name="belt" value="<?php echo ( ( empty($data[0]['belts']) ) ? ('') : ($data[0]['belts']) ) ?>" data-type="belt" data-parent="experience"/>
        </label>
    </div>
    <label>Other Comments:
        <textarea placeholder="Comments" name="comments" ><?php echo ( ( empty($data[0]['comments']) ) ? ('') : ($data[0]['comments']) )  ?></textarea>
    </label>
    <input class="cancel margin5_bottom" type="button" value="Cancel" />
    <input class="submit" type="submit" value="Edit" />
</form>