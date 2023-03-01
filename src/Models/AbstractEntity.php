<?php /** @noinspection PhpDocMissingThrowsInspection */


	namespace MehrIt\HetznerDnsApi\Models;


	use DateTime;
	use DateTimeInterface;
	use DateTimeZone;
	use JsonSerializable;

	abstract class AbstractEntity implements JsonSerializable
	{
		/**
		 * Creates a new instance from given data array
		 * @param array $data The data
		 * @return static the new instance
		 */
		public static abstract function fromArray(array $data);

		/**
		 * Returns the record as array
		 * @return array The record data
		 */
		public abstract function toArray(): array;


		/**
		 * @inheritDoc
		 */
		public function jsonSerialize() {
			return $this->toArray();
		}


		/**
		 * Converts the given date to string
		 * @param DateTimeInterface $date The date
		 * @return string The date string
		 */
		protected static function dateToString(DateTimeInterface $date): string {

			// output date as UTC string
			return (new DateTime('@' . $date->getTimestamp(), new DateTimeZone('UTC')))->format('Y-m-d\\TH:i:s\\Z');
		}

		/**
		 * Converts a string to date time
		 * @param string $date The date string
		 * @return DateTime The date time instance
		 */
		protected static function dateFromString(string $date): DateTime {
            try {
                return (new DateTime($date));
            } catch (\Exception $exception) {
                // convert strange date string 2020-01-01 01:02:03.111111111 +0000 UTC m=+123.456789012
                if (preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}).*UTC.*$/', $date, $matches)) {
                    $dateString = sprintf('%s-%s-%s %s:%s:%s',$matches[1],$matches[2],$matches[3],$matches[4],$matches[5],$matches[6]);
                    return new DateTime($dateString);
                }

                throw new \Exception('Could not convert string to date: '. $date);
            }
		}


	}
