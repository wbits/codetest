inSided - The best community driven platform

Hi and welcome to the most exciting part of the hiring process, coding :)

We’re looking for passionate and business oriented engineers able to transform a legacy code in a nice and clean codebase.
That's why this exercise is about refactoring and the way you’re able to model a domain using OOP (hint: state and behavior are both important).

Take your time. We're not checking your velocity. We know context is everything, and in this specific context we'd like to see your way of reasoning.
So please keep the whole git history, so we can see the steps you took while solving the problem.

Constraints

- You can't use framework apart from phpunit (already in composer.json)
- No databases. There are few repositories with a in-memory implementation. Feel free to change them, but be aware we're not looking for performance here.
- Once you've done [git bundle]

The exercise

The current version of our community was developed by a fella named Leo, who has moved on to new adventures.
It works somehow, but we're getting weird bugs and most important we're struggling adding new features without introducing new defects.
Our business knows that tech debt should be repaid, as well any other debts ;)
So after a data driven investigation, we came to the conclusion that we need to fix our core part.

Some context

Our platform is a community driven forum. We have several communities. Each of them managed by different admins.

- Users can read/write posts and edit/delete their own posts.
- Admins can also update and delete other users posts.

You can assume that when a request came to this application, the identity of the user has been already validated.
Also, since our endpoints are used by third party companies, you can't change them. So url, parameters and response should stay the same.

Main concepts

- Community is a container of posts.
- Post can be an Article, Conversation or Question. It has has title (sometime is optional), text and type.
- User can be an Author, Moderator or Admin. It has username and role.
- Comment can by a reply to an Article, a Conversation or a Question. It does have parent and text.

Business constraints:

- An Article can't have a parent.
- A Conversation doesn't have a title.
- A Comment can have as parent an Article, a Conversation or a Question. It has only a text.
- An Article can have comments disabled. !!!

Known bugs

- If we update a comment, we end up with a duplicated one.
- If we disallow comments for an article, we end up deleting all of them. !!!

Features requested

- We would like to show the username for each post.

We're expecting an incremental solution and you can stop whenever you like.
The code should work without bugs and should be fully tested (so we can avoid regression during the next refactoring).
It's also important you design your code so it will be open for extension, but closed for modification or if you prefer you should enforce business invariants.


LEFT TO DO

- methods to disallow comments, you should introduce a bug
- tests we can use to validate the refactoring is working (controller endpoints)
- assess if the tests is too complicated or too simple
- ask someone to do it in timeboxing (2h), yes I know we're not checking the velocity but we need to understand how long does it take more or less




