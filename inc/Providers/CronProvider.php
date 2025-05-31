<?php

namespace ChatAi\Providers;

use ChatAi\Contracts\Registrable;
use ChatAi\Services\EmbeddingService;

readonly class CronProvider implements Registrable {

	public function __construct( private EmbeddingService $embeddingService ) { }

	public static function uninstall(): void {
		EmbeddingService::cleanup();
	}

	public function register(): void {
		add_filter( 'cron_schedules', [ $this, 'registerSchedules' ] );
	}

	public function runEvent(): void {
		$this->embeddingService->createEmbeddings();
	}

	public function registerEvents(): void {
		$this->embeddingService->createColumns();
		$this->embeddingService->registerEvent();
	}

	public function unregisterEvents(): void {
		$this->embeddingService->unregisterEvent();
	}

	public function registerSchedules(): array {
		$minute_in_seconds = 60;

		$chatai_schedule = [
			[
				'key'                 => 'every_1_minute',
				'label'               => esc_html__( 'Every 1 Minute', 'chat-ai' ),
				'interval_in_minutes' => 1,
			],
			[
				'key'                 => 'every_5_minutes',
				'label'               => esc_html__( 'Every 5 Minutes', 'chat-ai' ),
				'interval_in_minutes' => 5,
			],
			[
				'key'                 => 'every_10_minutes',
				'label'               => esc_html__( 'Every 10 Minutes', 'chat-ai' ),
				'interval_in_minutes' => 10,
			],
			[
				'key'                 => 'every_15_minutes',
				'label'               => esc_html__( 'Every 15 Minutes', 'chat-ai' ),
				'interval_in_minutes' => 15,
			],
			[
				'key'                 => 'every_30_minutes',
				'label'               => esc_html__( 'Every 30 Minutes', 'chat-ai' ),
				'interval_in_minutes' => 30,
			],
		];

		foreach ( $chatai_schedule as $schedule ) {
			[ 'key' => $key, 'label' => $label, 'interval_in_minutes' => $interval_in_minutes ] = $schedule;

			$schedules[ $key ] = [
				'interval' => $interval_in_minutes * $minute_in_seconds,
				'display'  => $label,
			];
		}

		return apply_filters( 'chatai_cron_schedules', $schedules );
	}
}
