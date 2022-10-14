<div class="w-full p-8">
  <div>

    <nav class="flex" aria-label="Breadcrumb">
      <ol role="list" class="flex items-center space-x-4">
        <li>
          <div class="flex">
            <a href="<?= $header_page_1_url ?>" class="text-sm font-medium text-gray-500 hover:text-gray-700"><?= $header_page_1 ?></a>
          </div>
        </li>
        <?php if($header_page_2 != null){ ?>
        <li>
          <div class="flex items-center">
            <!-- Heroicon name: mini/chevron-right -->
            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
            <a href="<?= $header_page_2_url ?>" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"><?= $header_page_2 ?></a>
          </div>
        </li>
        <?php } if($header_page_3 != null){ ?>
        <li>
          <div class="flex items-center">
            <!-- Heroicon name: mini/chevron-right -->
            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
            <a href="<?= $header_page_3_url ?>" aria-current="page" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"><?= $header_page_3 ?></a>
          </div>
        </li>
        <?php } ?>
      </ol>
    </nav>
  </div>
  <div class="mt-2 md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"><?= $header_page_name ?></h2>
    </div>
    <?php if($header_page_buttons != null){ ?>
    <div class="mt-4 flex flex-shrink-0 md:mt-0 md:ml-4 flex-wrap">
        <?= $header_page_buttons ?>
    </div>
    <?php } ?>
  </div>
</div>