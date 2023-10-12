class JeuMotus {
    word;
    wordLenght;
    first_pos;
    last_pos;
    letter_pos;
    game_ligne;
    user_word;
    letterTd;
    score;
    temp_secretword;
    constructor(motSecret) {
        this.motSecret = motSecret.toUpperCase();
        this.motLenght = motSecret.length;
        this.rowCount = 0;
    }
}

// Initialiser les variables ==> Créer le tableau
function InitializeGame(motSecret)
{
    const jeu = new JeuMotus(motSecret);
    word = jeu.motSecret;
    wordLenght = jeu.motLenght;
    first_pos = 1;
    last_pos = jeu.motLenght;
    game_ligne = 1;
    letter_pos = 0;
    user_word = [];
    temp_secretword = [];
    user_word.push(jeu.motSecret[0]);

        while (jeu.rowCount < 6)
        {
            createNewRow();
            jeu.rowCount++;
        }
}

// Creation d'une ligne du tableau
function createNewRow()
{
    const wordContainer = document.getElementById('word-container');
    const newRow = document.createElement('tr');
    newRow.className = 'row';

    for (let i = 0; i < wordLenght; i++)
    {
        letterTd = document.createElement('td');
        letterTd.className = 'letter';
        letterTd.textContent = '';
        newRow.appendChild(letterTd);
        if (letter_pos == 0)
        {
            letterTd.textContent = word[0];
            letter_pos++;
        }
    }
    wordContainer.appendChild(newRow);
}


// Gestion d'effacement de lettre par l'utilisateur
function handleBackspace(event)
{
    if (first_pos < letter_pos)
    {
        if (event.key ==='Backspace' && letter_pos != 1 )
        {
            const letterDivs = document.querySelectorAll('.letter');
            const lastFilledDiv = Array.from(letterDivs).findLast(div => div.textContent !== '');
            letter_pos--;
            user_word.pop();
            if (lastFilledDiv)
            {
                lastFilledDiv.textContent = '';
            }
        }
    }     
}

// Utilisteur Appuie sur Entrer pour Finaliser son mot
function handleEnterKey(event)
{    
    if (event.key === 'Enter' && letter_pos == (wordLenght * game_ligne))
    {
        if (game_ligne < 7)
        {
            compareWords(user_word, word);
            if (!isWordCorrect(user_word, word))
            {
                user_word = [];
            }
            first_pos = (game_ligne * wordLenght) + 1;
            game_ligne++;
            last_pos = (wordLenght * game_ligne);
        }
        if (game_ligne == 7)
        {
            endGame(false, 0);
        }
    }
}

// Verifie rapidement le mot complet
function isWordCorrect(selectedWord, word)
{
    return selectedWord === word;
}

// Gestion de la sélection des lettres par l'utilisateur
function handleLetterSelection(event)
{
    const letter = event.key.toUpperCase();

    if (letter_pos != (wordLenght * game_ligne) && game_ligne < 7)
    {
        // Vérifie si la touche pressée est une lettre de l'alphabet
        if (/^[A-Z]$/.test(letter))
        {
            const letterDivs = document.querySelectorAll('.letter');
            user_word.push(letter);
            letter_pos++;

            // Parcours les divs et remplit la première div vide avec la lettre sélectionnée
            for (let i = 1; i < letterDivs.length; i++)
            {
                if (letterDivs[i].textContent == '' && first_pos == (letter_pos))
                {
                    letterDivs[i].textContent = word[0];
                    user_word.pop();
                    user_word.push(word[0]);
                    break;
                }
                if (letterDivs[i].textContent == '')
                {
                    letterDivs[i].textContent = letter;
                    break;
                }
            }
        }
    }
}


// Verifier les cases jaunes et les lettres double
function check_yellow(secretWord, input_word, actual_letter)
{

    for (let i = 0; i < secretWord.length; i++)
    {
        temp_secretword.push(secretWord[i]);
    }

    for (let i = 0; i < secretWord.length; i++)
    {
        if (input_word[i] == temp_secretword[i])
        {
            temp_secretword[i] = '.';
        }
    }

    for (let i = 0; i < secretWord.length; i++)
    {
        if (input_word[actual_letter] == temp_secretword[i])
        {
            temp_secretword[i] = '.';
            return true;
        }
    }
    return false;
}

//Vérifie si le mot est bon
function compareWords(selectedWord, word)
{

    const wordContainer = document.getElementById('word-container');
    const letterDivs = wordContainer.getElementsByClassName('letter');
    correct_answer = 0;

    for (let z = 0, i = first_pos - 1; i < last_pos; i++, z++) {

        if (selectedWord[z] == word[z])
        {
            letterDivs[i].classList.add('correct');
            correct_answer++;
        }
        else if (check_yellow(word, selectedWord, z))
        {
            letterDivs[i].classList.add('present');
            letterDivs[i].classList.add('present::before');
        } else
        {
            letterDivs[i].classList.add('not-in-word');
        }
    } temp_secretword = [];

    if (correct_answer == wordLenght)
    {
        score = 7 - game_ligne;
        if (game_ligne == 1) score = 10;
        game_ligne = 7;
        endGame(true, score);
    }
}

// Message fin de jeu
function endGame(isVictory, score)
{
    const messageWin = 'Félicitations, vous avez trouvé le mot !\n\n\n Vous gagnez : ' + score + 'points !';
    const messageLose = 'Mince, vous n\'avez pas trouver le mot .\n\n\n Le mot était : ' + word + '\n\n\n vous gagnez 0 point .';

    if (isVictory == true)
    {
        var gameOverSound = document.getElementById('gameWinSound');
        gameOverSound.play();
        afficherMessage(messageWin);
        send(score);

    } else {
        var gameOverSound = document.getElementById('gameLoseSound');
        gameOverSound.play();
        afficherMessage(messageLose);
        send(score);
    }
}

//afficher message
function afficherMessage(message) {
    const messageWithLineBreaks = message.replace(/\n/g, "<br>");
    const pannel = document.getElementById('pannel');
    const newRow = document.createElement('div');

    newRow.className = "pannel-message";
    newRow.innerHTML = messageWithLineBreaks;
    pannel.appendChild(newRow);
    pannel.style.visibility = 'visible';
}


  function send(score) {

    var xhr = new XMLHttpRequest();
    var url = "Wall_of_fame.php";
    var params = "score=" + score;

    xhr.open("POST", url, true);

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xhr.send(params);
}


document.addEventListener('keydown', handleLetterSelection);

document.addEventListener('keydown', handleBackspace);

document.addEventListener('keydown', handleEnterKey);