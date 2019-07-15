# inSided - The best community driven platform

Hi and welcome to the most exciting part of the hiring process, coding :)

We’re looking for passionate and business oriented engineers able to transform
a legacy code in a nice and clean codebase. That's why this exercise is about
refactoring and the way you’re able to model a domain using OOP (hint: state
and behavior are both important).

Take your time. We're not checking your velocity. We know context is
everything, and in this specific context we'd like to see your way of
reasoning. So please keep the whole git history, so we can see the steps you
took while solving the problem.

### Constraints

- You can't use framework apart from phpunit (already in composer.json)
- No databases. You can use repositories with a in-memory implementation.
  Feel free to change them, but be aware we're not looking for performance here.
- Once you've done [git bundle]

### The exercise

The current version of our community was developed by a fella named Leo, who
has moved on to new adventures. It works somehow, but we're getting weird bugs
and most importantly we're struggling with adding new features without
introducing new defects. We also have some unnecessary code in codebase which adds more complexity than needed.
Our business knows that tech debt should be repaid, as well as any other debts ;) So after a data driven investigation,
we came to the conclusion that we need to fix our core codebase.

### Some context

Our platform is a community driven forum. We have several communities. Each
community is managed by different moderators.

- Users can read/write posts and edit/delete their own posts.
- Admins can also update and delete other users posts.

You can assume that when a request comes to this application, the identity of
the user has been already validated. Also, since our endpoints are used by
third party companies, you can't change them. So url, parameters and response
should stay the same.

### Main concepts

- Community is a container of posts.
- Post can be an Article, Conversation or Question.
  Post has title (which is sometimes optional), text and type.
- User can be an Author, Moderator or Admin.
  User has username and role.
- Comment can be a reply to an Article, a Conversation or a Question.
  Comment has parent and text.

### Business constraints:

- A Post can not have a parent.
- A Conversation doesn't have a title.
- A Comment can have as parent an Article, a Conversation or a Question. Comment contains text.
- An Article can have commenting disabled.

### Known bugs

- If we update a post, we end up with a duplicated one.
- If we disable commenting for an article, we end up deleting all comments from the article.

### Requested features

- We would like to show the username for each post.

We're expecting an incremental solution and you can stop whenever you like. The
code should work without bugs and should be fully tested (so we can avoid
regression during the next refactoring). It's also important you design your
code so it will be open for extension, but closed for modification or if you
prefer you should enforce business invariants.

# Submitting the code

Once you are happy with your solution you can use the following command to
create a bundle file:

    git bundle create insided-solution.bundle master

You can submit the bundle via the email.
