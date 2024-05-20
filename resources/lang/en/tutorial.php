<?php

return [
    'head' => 'The Tutorial',
    'text' => "Welcome to our online voting system, designed for simple and efficient voting. 
        This guide provides detailed instructions for using the application from the perspective of an 
        unauthenticated user, an authenticated user, and an administrator.",
    
    'unauth_user' => "Unauthenticated User",
    'unauth_user_text' => "As an unauthenticated user, you have the option to participate in voting through a few simple steps. First, visit the application's homepage, where you can enter the voting question's entry code provided, for example, by the lecturer. After entering the code, you will be redirected to the voting question page, where you can cast your vote. You can enter the code into the input field or append it to the URL address (https://nodeXX.webte.fei.stuba.sk/abcde). After voting, you will see a graphical display of the voting results, which can be displayed either as a list or as a word cloud, depending on how the question was defined by the creator.",
    'auth_user' => "Authenticated User",
    'auth_user_text' => "As an authenticated user, you have access to a broader range of functionalities. After successfully registering and logging into the application, you can change your password and create voting questions. When creating questions, you can choose between questions with a correct answer selection and questions with an open-ended answer. You can define which subject the questions belong to. For open-ended questions, you can set whether the results will be displayed as a list or in the form of a word cloud. Additionally, you can edit existing questions.",
    'admin' => "Administrator",
    'admin_text' => "As an administrator, you have access to all the functionalities of an authenticated user, and additionally, you can manage the voting questions of all users. When creating a new voting question, you can specify on whose behalf you are creating it.",
    'other_tetx' =>""
];
