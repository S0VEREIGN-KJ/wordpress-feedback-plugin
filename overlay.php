<?php 
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Template Name: Overlay
 * Author: Karl Jasper G. Del Rosario
 */
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
     .overlay {
        position: fixed;
        top: 50%; 
        left: 50%; 
        transform: translate(-50%, -50%); 
        width: 100vw; 
        height: 100vh; 
        background-color: rgba(0, 0, 0, 0.1); 
        z-index: 1000;
        display: flex; 
        align-items: center; 
        justify-content: center;
        overflow: hidden; 
     }
     .survey-box {
        position: relative;
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 600px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
     }
     h2 {
        text-align: center;
        margin: auto;
        margin-bottom: 5px;
     }
     .question {
        font-weight: bold;
     }
     .emoji-container {
        text-align: center;
        font-size: 30px;
        margin-bottom: 5px;
     }
     .hidden-emoji {
     display: none;
     }
     .options {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
     }
     .options label {
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
     }
     .submit-btn {
        display: block;
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        background-color: black;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
     }
     .question-container {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background: #f9f9f9;
     }
     .comment-box {
        margin-top: 20px;
     }
     .comment-box textarea {
        width: 95%;
        height: 50px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: none;
     }
     .toggle-btn {
        position: fixed;
        bottom: 20px;
        right: 50px;
        cursor: pointer;
        width: 70px;
        height: 70px;
        border: 2px solid black;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }
    .description {
        position: fixed;
        bottom: 80px;
        right: 10px;
        background: black;
        color: white;
        padding: 10px;
        border-radius: 5px;
        display: none;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    .toggle-btn img {
        width: 50px;
        height: 50px;
        transition: transform 0.3s ease-in-out;
    }
    .toggle-btn:hover {
        transform: scale(1.1);
    }
    .toggle-btn.active {
        transform: rotate(360deg);
        transition: transform 0.5s ease-in-out;
    }
     .close-btn {
        position: absolute;
        top: 15px;
        right: 20px;
        background: red;
        color: white;
        border: none;
        border-radius: 2px;
        width: 30px;
        height: 30px;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
     }
     .close-btn:hover {
        background: darkred;
     }
    </style>
</head>

<body>

<div class="description" id="description-box" style="display: block; opacity: 1;">Click to send feedback</div>
<button class="toggle-btn">
    <img src="<?php echo plugins_url('images/feedback_icon.png', __FILE__); ?>" alt="Open Feedback">
</button>


<div class="overlay" style="display: none; opacity: 0;">
    <div class="survey-box">
        <button class="close-btn">&times;</button>
        <h2>Website Feedback</h2>
        <form method="POST" id="surveyForm">
            <!-- q1 -->
            <div class="question-container">
                    <div class="question">The website loads quickly and responds without delays *</div>
                    <div class="emoji-container" id="emoji-q1">😐</div>
                    <div class="options">
                        <label><input type="radio" name="q1" value="😀"> <span class="hidden-emoji">😀</span> Very Satisfied</label>
                        <label><input type="radio" name="q1" value="😊"> <span class="hidden-emoji">😊</span> Satisfied</label>
                        <label><input type="radio" name="q1" value="😐"> <span class="hidden-emoji">😐</span> Neutral</label>
                        <label><input type="radio" name="q1" value="😕"> <span class="hidden-emoji">😕</span> Unsatisfied</label>
                        <label><input type="radio" name="q1" value="😨"> <span class="hidden-emoji">😨</span> Very Unsatisfied</label>
                    </div>
                </div>
                <!-- q2 -->
                <div class="question-container">
                    <div class="question">The website is easy to understand and navigate *</div>
                    <div class="emoji-container" id="emoji-q2">😐</div>
                    <div class="options">
                        <label><input type="radio" name="q2" value="😀"> <span class="hidden-emoji">😀</span> Very Satisfied</label>
                        <label><input type="radio" name="q2" value="😊"> <span class="hidden-emoji">😊</span> Satisfied</label>
                        <label><input type="radio" name="q2" value="😐"> <span class="hidden-emoji">😐</span> Neutral</label>
                        <label><input type="radio" name="q2" value="😕"> <span class="hidden-emoji">😕</span> Unsatisfied</label>
                        <label><input type="radio" name="q2" value="😨"> <span class="hidden-emoji">😨</span> Very Unsatisfied</label>
                    </div>
                </div>
                <!-- q3-->
                <div class="question-container">
                    <div class="question">The website is visually appealing *</div>
                    <div class="emoji-container" id="emoji-q3">😐</div>
                    <div class="options">
                        <label><input type="radio" name="q3" value="😀"> <span class="hidden-emoji">😀</span> Very Satisfied</label>
                        <label><input type="radio" name="q3" value="😊"> <span class="hidden-emoji">😊</span> Satisfied</label>
                        <label><input type="radio" name="q3" value="😐"> <span class="hidden-emoji">😐</span> Neutral</label>
                        <label><input type="radio" name="q3" value="😕"> <span class="hidden-emoji">😕</span> Unsatisfied</label>
                        <label><input type="radio" name="q3" value="😨"> <span class="hidden-emoji">😨</span> Very Unsatisfied</label>
                    </div>
                </div>
                    <!-- q4 -->
                <div class="question-container">
                    <div class="question">The website has readable text and content *</div>
                    <div class="emoji-container" id="emoji-q4">😐</div>
                    <div class="options">
                        <label><input type="radio" name="q4" value="😀"> <span class="hidden-emoji">😀</span> Very Satisfied</label>
                        <label><input type="radio" name="q4" value="😊"> <span class="hidden-emoji">😊</span> Satisfied</label>
                        <label><input type="radio" name="q4" value="😐"> <span class="hidden-emoji">😐</span> Neutral</label>
                        <label><input type="radio" name="q4" value="😕"> <span class="hidden-emoji">😕</span> Unsatisfied</label>
                        <label><input type="radio" name="q4" value="😨"> <span class="hidden-emoji">😨</span> Very Unsatisfied</label>
                    </div>
                </div>
                    <!-- q5 -->
                <div class="question-container">
                    <div class="question">The website's design is modern and professional *</div>
                    <div class="emoji-container" id="emoji-q5">😐</div>
                    <div class="options">
                        <label><input type="radio" name="q5" value="😀"> <span class="hidden-emoji">😀</span> Very Satisfied</label>
                        <label><input type="radio" name="q5" value="😊"> <span class="hidden-emoji">😊</span> Satisfied</label>
                        <label><input type="radio" name="q5" value="😐"> <span class="hidden-emoji">😐</span> Neutral</label>
                        <label><input type="radio" name="q5" value="😕"> <span class="hidden-emoji">😕</span> Unsatisfied</label>
                        <label><input type="radio" name="q5" value="😨"> <span class="hidden-emoji">😨</span> Very Unsatisfied</label>
                    </div>
                </div>
            <div class="comment-box">
                <label for="comments"><strong>Any additional feedback? <em>(Optional)</em></strong></label>
                <textarea id="comments" name="comments" placeholder="Write your suggestion and comments ..."></textarea>
            </div>
            <button type="submit" class="submit-btn">SUBMIT</button>
        </form>
    </div>
</div>

<script>

                        //*remove (undefined)*/      localStorage.removeItem("surveySubmitted"); 
                        //* show (true)* /          console.log(localStorage.getItem("surveySubmitted"));
                        //              ****for debugging F12, console tab, apply pasting*****
    document.addEventListener("DOMContentLoaded", function () {
    let overlay = document.querySelector(".overlay");
    let submitButton = document.querySelector(".submit-btn");
    const form = document.querySelector("#surveyForm");
    let closeButton = document.querySelector(".close-btn");
    let toggleButton = document.querySelector(".toggle-btn");
    let descriptionBox = document.querySelector("#description-box");

    if (!overlay) {
        console.error("Overlay element not found!");
        return;
    }

    function showDescription() {
        descriptionBox.style.display = "block";
        setTimeout(() => {
            descriptionBox.style.opacity = "1";
            setTimeout(() => {
                descriptionBox.style.opacity = "0";
                setTimeout(() => {
                    descriptionBox.style.display = "none";
                }, 500);
            }, 4000);
        }, 100);
    }

    function updateUI() {
        if (localStorage.getItem("surveySubmitted") === "true") {
            descriptionBox.textContent = "Thank you for your feedback!";
            descriptionBox.style.display = "block";
            descriptionBox.style.transition = "opacity 0.5s ease-in-out";
            descriptionBox.style.opacity = "0";

            // Fade out and hide toggleButton
            toggleButton.style.transition = "opacity 0.5s ease-in-out";
            toggleButton.style.opacity = "0";
            setTimeout(() => {
                toggleButton.style.display = "none";
            }, 500);

        } else {
            setTimeout(() => {
                showDescription();
                const interval = setInterval(() => {
                    if (localStorage.getItem("surveySubmitted") === "true") {
                        clearInterval(interval);
                        updateUI(); // Update UI after submission
                    } else {
                        showDescription();
                    }
                }, 7000);
            }, 3000);
        }
    }

    // ** Update UI on Page Load **
    updateUI();

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        let formData = new FormData(this);
        formData.append("comments", document.getElementById("comments").value.trim());
        formData.append('action', 'handle_feedback_submission');
        formData.append('security', ajax_object.nonce);


        submitButton.disabled = true;
        submitButton.textContent = "Submitting...";

        fetch(ajax_object.ajax_url, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                localStorage.setItem("surveySubmitted", "true");

                // ** Update UI without refresh **
                updateUI();

                let popup = document.createElement("div");
                popup.id = "popupMessage";
                popup.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: rgba(0, 0, 0, 0.8);
                    color: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    font-size: 16px;
                    opacity: 1;
                    transition: opacity 0.5s ease-in-out;
                    z-index: 1000;
                `;
                popup.textContent = "Thank you for your feedback!";
                document.body.appendChild(popup);

                setTimeout(() => {
                    popup.style.opacity = "0";
                    setTimeout(() => {
                        popup.remove();
                    }, 500);
                }, 1000);

                // Hide survey overlay smoothly
                overlay.style.transition = "opacity 0.5s ease-in-out";
                overlay.style.opacity = "0";
                setTimeout(() => {
                    overlay.style.display = "none";
                }, 500);
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("There was an issue submitting the survey. Please contact admin.");
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.textContent = "SUBMIT";
        });
    });

    // ** Emoji Change Animation **
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function () {
            let questionContainer = this.closest('.question-container');
            let emojiContainer = questionContainer.querySelector('.emoji-container');

            document.querySelectorAll('.emoji-container').forEach(emoji => {
                emoji.classList.remove('animated');
            });

            emojiContainer.classList.add('animated');
            emojiContainer.textContent = this.value;
        });
    });

    closeButton.addEventListener("click", function () {
        overlay.style.opacity = "0";
        setTimeout(() => {
            overlay.style.display = "none";
            toggleButton.classList.remove("active");
        }, 500);
    });

    toggleButton.addEventListener("click", function () {
        if (localStorage.getItem("surveySubmitted") !== "true") {
            overlay.style.display = "flex";
            setTimeout(() => {
                overlay.style.opacity = "1";
            }, 100);
            toggleButton.classList.add("active");
        }
    });
});

</script>

</body>

