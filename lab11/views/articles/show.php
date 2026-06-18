<?php if ($article === null): ?>

<h2>404</h2>
<p>Статья не найдена.</p>

<?php else: ?>

<article class="article-page">
    <h2><?= htmlspecialchars($article->name, ENT_QUOTES, 'UTF-8') ?></h2>

    <p class="author">
        Автор:
        <?php if ($author !== null): ?>
            <strong><?= htmlspecialchars($author->nickname, ENT_QUOTES, 'UTF-8') ?></strong>
        <?php else: ?>
            <strong>не найден</strong>
        <?php endif; ?>
    </p>

    <p class="date">
        Дата создания: <?= htmlspecialchars($article->createdAt, ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        <?= nl2br(htmlspecialchars($article->text, ENT_QUOTES, 'UTF-8')) ?>
    </p>

    <p>
        <a href="/article/<?= $article->id ?>/edit">Редактировать статью</a>
    </p>
</article>

<hr>

<section class="comments">
    <h2>Комментарии</h2>

    <?php if (isset($_GET['comment_error']) && $_GET['comment_error'] === 'empty'): ?>
        <p class="message error">Комментарий не может быть пустым.</p>
    <?php endif; ?>

    <?php if (empty($comments)): ?>
        <p>Комментариев пока нет.</p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment" id="comment<?= $comment->id ?>">
                <p class="comment-meta">
                    <strong>
                        <?= htmlspecialchars($comment->authorNickname ?? 'Неизвестный автор', ENT_QUOTES, 'UTF-8') ?>
                    </strong>

                    <span>
                        <?= htmlspecialchars($comment->createdAt, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </p>

                <p>
                    <?= nl2br(htmlspecialchars($comment->text, ENT_QUOTES, 'UTF-8')) ?>
                </p>

                <p>
                    <a href="/comments/<?= $comment->id ?>/edit">Редактировать</a>
                </p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h3>Добавить комментарий</h3>

    <form method="post" action="/articles/<?= $article->id ?>/comments" class="comment-form">
        <div class="form-group">
            <label for="text">Текст комментария</label>
            <textarea id="text" name="text" rows="5" required></textarea>
        </div>

        <button type="submit">Добавить комментарий</button>
    </form>
</section>

<p>
    <a href="/">← Назад к списку статей</a>
</p>

<?php endif; ?>