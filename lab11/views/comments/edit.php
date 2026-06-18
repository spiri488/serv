<h2>Редактирование комментария</h2>

<?php if ($error !== ''): ?>
    <p class="message error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<?php if ($success !== ''): ?>
    <p class="message success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="post" action="/comments/<?= $comment->id ?>/edit" class="comment-form">
    <div class="form-group">
        <label for="text">Текст комментария</label>
        <textarea id="text" name="text" rows="7" required><?= htmlspecialchars($comment->text, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button type="submit">Сохранить</button>
</form>

<p>
    <a href="/articles/<?= $comment->articleId ?>#comment<?= $comment->id ?>">
        ← Вернуться к статье
    </a>
</p>